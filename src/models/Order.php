<?php

namespace eluhr\shop\models;

use eluhr\shop\interfaces\PaymentProvider;
use eluhr\shop\models\base\Order as BaseOrder;
use eluhr\shop\Module;
use Ramsey\Uuid\Uuid;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\mail\MailerInterface;
use yii\swiftmailer\Mailer;

/**
 * This is the model class for table "sp_order".
 *
 * @property mixed $detailUrl
 * @property mixed $statusLabel
 * @property mixed $invoiceUrl
 * @property int $totalAmount
 * @property mixed $invoiceSavePath
 * @property \yii\mail\MailerInterface $mailer
 * @property \eluhr\shop\Module $module
 * @property bool $isPaid
 * @property string $fullName
 */
class Order extends BaseOrder
{
    /**
     * ENUM field values
     */
    const TYPE_PAYPAL = 'PAYPAL';
    const TYPE_SAFERPAY = 'SAFERPAY';
    const TYPE_PAYREXX = 'PAYREXX';
    const TYPE_PREPAYMENT = 'PREPAYMENT';

    public const INFO_MAIL_STATUS_SENT = 1;
    public const INFO_MAIL_STATUS_NOT_SENT = 0;

    public const SCENARIO_INTERNAL_NOTES = 'internal-notes';
    public const SCENARIO_MOVE = 'move';

    public $moduleId = 'shop';

    private $_invoiceSavePath;

    public const ALL = 'ALL';

    public static function generateId()
    {
        $uuid = Uuid::uuid4()->toString();
        if (ShopSettings::shopGeneralShortOrderId()) {
            return substr($uuid, 0, 13);
        }
        return $uuid;
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->surname;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['fullName'] = Yii::t('shop', 'Full Name');
        $attributeLabels['id'] = Yii::t('shop', 'Order number', [], 'de');
        return $attributeLabels;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_INTERNAL_NOTES] = [
            'internal_notes',
            'shipment_link',
            'invoice_number'
        ];
        $scenarios[self::SCENARIO_MOVE] = [
            'status'
        ];
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            'shipment_link',
            'url'
        ];
        $rules[] = [
            'info_mail_has_been_sent',
            'default',
            'value' => self::INFO_MAIL_STATUS_NOT_SENT
        ];
        $rules[] = [
            [
                'internal_notes',
                'shipment_link',
                'invoice_number'
            ],
            'safe',
            'on' => self::SCENARIO_INTERNAL_NOTES
        ];
        $rules[] = [
            'internal_notes',
            'string',
            'max' => 250,
            'on' => self::SCENARIO_INTERNAL_NOTES
        ];
        $rules[] = [
            'customer_details',
            'string',
            'max' => 250
        ];
        $rules[] = ['type', 'in', 'range' => [
            self::TYPE_PAYPAL,
            self::TYPE_SAFERPAY,
            self::TYPE_PAYREXX,
            self::TYPE_PREPAYMENT,
        ]];
        return $rules;
    }

    public function attributeHints()
    {
        $attributeHints = parent::attributeHints();
        $attributeHints['internal_notes'] = Yii::t('shop', 'This notes are not visible to the customer');
        return $attributeHints;
    }

    public static function findByPaypalId($paypalId)
    {
        return self::findOne(['paypal_id' => $paypalId]);
    }

    public static function statusData(string $activeData): array
    {
        return [
            self::STATUS_RECEIVED => [
                'backwards' => false,
                'forwards' => self::STATUS_RECEIVED_PAID
            ],
            self::STATUS_RECEIVED_PAID => [
                'backwards' => self::STATUS_RECEIVED,
                'forwards' => self::STATUS_IN_PROGRESS
            ],
            self::STATUS_IN_PROGRESS => [
                'backwards' => self::STATUS_RECEIVED_PAID,
                'forwards' => self::STATUS_SHIPPED
            ],
            self::STATUS_SHIPPED => [
                'backwards' => self::STATUS_IN_PROGRESS,
                'forwards' => self::STATUS_FINISHED
            ],
            self::STATUS_FINISHED => [
                'backwards' => self::STATUS_SHIPPED,
                'forwards' => false
            ],
        ][$activeData];
    }

    public function getDetailUrl()
    {
        return Url::to(['/shop/orders/detail', 'orderId' => $this->id], true); // true so link does work in email
    }

    public function getInvoiceUrl()
    {
        return Url::to(['/shop/shopping-cart/invoice', 'orderId' => $this->id]);
    }

    public function getStatusLabel()
    {
        return Html::tag('span', self::statusText($this->status), ['class' => 'label label-primary']);;
    }

    public static function statusText($status)
    {
        $data = [
            self::STATUS_PENDING => Yii::t('shop', '__STATUS_PENDING__'),
            self::STATUS_RECEIVED => Yii::t('shop', '__STATUS_RECEIVED__'),
            self::STATUS_RECEIVED_PAID => Yii::t('shop', '__STATUS_RECEIVED_PAID__'),
            self::STATUS_IN_PROGRESS => Yii::t('shop', '__STATUS_IN_PROGRESS__'),
            self::STATUS_SHIPPED => Yii::t('shop', '__STATUS_SHIPPED__'),
            self::STATUS_FINISHED => Yii::t('shop', '__STATUS_FINISHED__'),
        ];

        return $data[$status] ?? '';
    }

    /**
     * @return bool
     */
    public function getIsPaid()
    {
        $paidStatus = [
            self::STATUS_RECEIVED_PAID,
            self::STATUS_IN_PROGRESS,
            self::STATUS_SHIPPED,
            self::STATUS_FINISHED
        ];
        return ($this->type === self::TYPE_PREPAYMENT && in_array($this->status, $paidStatus,
                    true)) || ($this->type === self::TYPE_PAYPAL && $this->is_executed);
    }

    public function checkout(PaymentProvider $provider)
    {
        $order = $provider->performCheckoutProcedure($this);
        if ($order !== null) {
            if ($order->save()) {
                return $this->sendConfirmMail();
            }
        }
        Yii::error($this->errors);
        return false;
    }

    protected function getModule(): Module
    {
        $module = Yii::$app->getModule($this->moduleId);
        if ($module instanceof Module) {
            return $module;
        }
        throw new InvalidConfigException('module id must be instance of ' . Module::class);
    }

    protected function getMailer(): MailerInterface
    {
        $mailer = $this->getModule()->mailer;
        /** @var MailerInterface $mailer */
        return Yii::$app->{$mailer};
    }

    public function sendConfirmMail()
    {
        /** @var Mailer $mailer */
        $mailer = $this->getMailer();
        $mailer->setViewPath('@eluhr/shop/views/mail');
        $mailer->htmlLayout = '@eluhr/shop/views/mail/layouts/html';
        $mailer->textLayout = '@eluhr/shop/views/mail/layouts/text';

        $message = $mailer->compose(
            [
                'html' => 'html/confirm',
                'text' => 'text/confirm'
            ],
            [
                'order' => $this
            ]
        );
        $message->setTo($this->email);
        $message->setBcc(array_filter(array_map('trim', explode(',', ShopSettings::shopMailConfirmBcc()))));
        $message->setReplyTo(ShopSettings::shopMailConfirmReplyTo());
        $message->setSubject(ShopSettings::shopMailConfirmSubject());

        if ($message->send()) {
            return true;
        }
        Yii::error('Error while sending confirmation mail to ' . $this->email, __METHOD__);
        return false;
    }

    public function sendInfoMail()
    {
        $this->info_mail_has_been_sent = self::INFO_MAIL_STATUS_SENT;
        /** @var Mailer $mailer */
        $mailer = $this->getMailer();
        $mailer->setViewPath('@eluhr/shop/views/mail');
        $mailer->htmlLayout = '@eluhr/shop/views/mail/layouts/html';
        $mailer->textLayout = '@eluhr/shop/views/mail/layouts/text';

        $message = $mailer->compose(
            [
                'html' => 'html/info',
                'text' => 'text/info'
            ],
            [
                'order' => $this
            ]
        );
        $message->setTo($this->email);
        $message->setReplyTo(ShopSettings::shopMailInfoReplyTo());
        $message->setSubject(ShopSettings::shopMailInfoSubject());

        if ($message->send() && $this->save()) {
            return true;
        }
        Yii::error('Error while sending info mail to ' . $this->email, __METHOD__);
        return false;
    }

    public function mailSetting($key, $value)
    {
        $section = 'shop.mail';
        $settings = Yii::$app->settings;

        $setting = $settings->getOrSet($key, $value, $section);

        if (is_bool($setting)) {
            $setting = $settings->get($key, $section, $value);
        }
        return $setting;
    }

    /**
     * @return int
     */
    public function getTotalAmount()
    {
        $total = 0;
        foreach ($this->orderItems as $position) {
            $total += $position->single_price * $position->quantity;
        }
        if ($this->discount_code_id !== null) {
            if ($this->discountCode->type === DiscountCode::TYPE_PERCENT) {
                $percent = 1 * ($this->discountCode->value / 100);
                $total += $total * $percent * -1;
            } else if ($this->discountCode->type === DiscountCode::TYPE_AMOUNT) {
                $total -= $this->discountCode->value;
            }

        }
        $total += (float)$this->shipping_price;

        return round($total, 2);
    }

    /**
     * @return int
     */
    public function getTotalNetAmount()
    {
        $total = 0;
        foreach ($this->orderItems as $position) {
            $total += $position->single_net_price * $position->quantity;
        }

        return round($total, 2);
    }


    /**
     * @return Pdf
     * @throws \yii\base\Exception
     */
    public function pdfObject()
    {
        $originalLogoPath = ShopSettings::shopInvoiceLogo();
        if (empty($originalLogoPath)) {
            $filepath = '';
        } else {
            $fileName = 'invoice-logo-' . md5(ShopSettings::shopInvoiceLogo()) . '.png';
            $directory = Yii::getAlias('@runtime/tmp/download');
            $filepath = $directory . DIRECTORY_SEPARATOR . $fileName;

            if (!is_file($filepath)) {
                if (FileHelper::createDirectory($directory)) {
                    if (file_put_contents($filepath, file_get_contents($originalLogoPath)) === false) {
                        Yii::$app->getModule('audit')->data(__METHOD__, 'Error while downloading file');
                    }
                } else {
                    Yii::$app->getModule('audit')->data(__METHOD__, 'Error while creating directory');
                }
            }
            $filepath = realpath($directory) . DIRECTORY_SEPARATOR . $fileName;
        }

        $pdf = new Pdf();

        $pdf->addPage(Yii::$app->controller->renderPartial('@eluhr/shop/views/shopping-cart/invoice.twig', [
            'order' => $this,
            'logoPath' => $filepath
        ]));

        return $pdf;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function generateInvoice()
    {
        $saveDirectory = Yii::getAlias('@runtime/tmp');
        if (FileHelper::createDirectory($saveDirectory) === false) {
            return false;
        }
        $savePath = $saveDirectory . DIRECTORY_SEPARATOR . $this->invoiceFileName();
        if ($this->pdfObject()->saveAs($savePath)) {
            $this->_invoiceSavePath = $savePath;
            return true;
        }
        return false;
    }

    public function invoiceFileName()
    {
        return 'rechnung-' . date('d_m_Y-H_i', strtotime($this->created_at)) . '-' . $this->id . '.pdf';
    }

    /**
     * @return mixed
     */
    public function getInvoiceSavePath()
    {
        return $this->_invoiceSavePath;
    }

    /**
     * get column status enum value label
     *
     * @param string $value
     *
     * @return string
     */
    public static function getStatusValueLabel($value)
    {
        $labels = self::optsStatus();
        if (isset($labels[$value])) {
            return $labels[$value];
        }
        return $value;
    }

    /**
     * column status ENUM value labels
     *
     * @return array
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_PENDING => Yii::t('shop', 'STATUS PENDING'),
            self::STATUS_RECEIVED => Yii::t('shop', 'STATUS RECEIVED'),
            self::STATUS_RECEIVED_PAID => Yii::t('shop', 'STATUS RECEIVED PAID'),
            self::STATUS_IN_PROGRESS => Yii::t('shop', 'STATUS IN PROGRESS'),
            self::STATUS_SHIPPED => Yii::t('shop', 'STATUS SHIPPED'),
            self::STATUS_FINISHED => Yii::t('shop', 'STATUS FINISHED'),
        ];
    }

    /**
     * column type ENUM value labels
     *
     * @return array
     */
    public static function optsType()
    {
        return [
            self::TYPE_PAYPAL => Yii::t('shop', 'PayPal'),
            self::TYPE_SAFERPAY => Yii::t('shop', 'Saferpay'),
            self::TYPE_PREPAYMENT => Yii::t('shop', 'Prepayment'),
            self::TYPE_PAYREXX => Yii::t('shop', 'Payrexx'),
        ];
    }

    public function execute()
    {
        if ($this->is_executed === 0) {
            $this->is_executed = 1;
            return $this->save();
        }
        return true;
    }

    public function deleteWithOrderItems()
    {
        $transaction = Yii::$app->db->beginTransaction();
        if ($transaction) {
            if (OrderItem::deleteAll(['order_id' => $this->id]) > 0) {
                if ($this->delete() !== false) {
                    $transaction->commit();
                    return true;
                }
            }
            $transaction->rollBack();
        }
        return false;
    }

    public function getShowBankDetails(): bool
    {
        if ($this->type === self::TYPE_PREPAYMENT) {
            return true;
        }
        return ShopSettings::shopMailShowBankDetails();
    }

    public function getItemsNetPricesByVat(): array
    {
        return (new Query())->select([
            'vat',
            'sum' => new Expression('TRUNCATE(SUM([[single_price]] * [[quantity]] * ([[vat]] / 100)), 2)')
        ])
            ->from(OrderItem::tableName())
            ->where(['order_id' => $this->id])
            ->groupBy(['vat'])
            ->all();
    }

    public function isOwn(): bool
    {
        return $this->user_id !== null && $this->user_id === Yii::$app->getUser()->getId();
    }

    /**
     * get column type enum value label
     * @param string $value
     * @return string
     */
    public static function getTypeValueLabel($value){
        $labels = self::optsType();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }
}
