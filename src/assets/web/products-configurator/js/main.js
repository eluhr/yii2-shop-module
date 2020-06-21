var sortableAllTagsEl = document.querySelector("[data-list='all-tags']");
var sortableAllTags = Sortable.create(sortableAllTagsEl, {
    group: {
        name: "tags",
        pull: "clone",
        put: false
    },
    sort: false,
    animation: 150
});

document.querySelectorAll("[data-list='filter-tags']").forEach(function (sortableFilterTagsEl) {
    Sortable.create(sortableFilterTagsEl, {
        group: {
            name: "tags",
            pull: false,
            put: notInSortableList
        },
        sort: true,
        animation: 150,
        onSort: function (event) {
            addTagToItem(event)
            var childs = Array.prototype.slice.call(event.to.children)
            var items = childs.map(function (item) {
                return item.dataset.tagId
            })
            var itemId = event.to.dataset.itemId;
            var url = event.to.dataset.sortUrl;
            fetch(url, {
                method: "POST",
                body: JSON.stringify({itemId: itemId, items: items})
            })
                .then(function (response) {
                    if (response.ok) {
                        return response.json();
                    }
                    throw Error("Fehler beim Speichern: " + response.statusText)
                })
                .then(function (json) {
                    console.log(json);
                })
                .catch(function (error) {
                    console.error(error);
                })
        }
    });
});

document.querySelectorAll("[data-list='product-tags']").forEach(function (sortableProductTagsEl) {
    Sortable.create(sortableProductTagsEl, {
        group: {
            name: "tags",
            pull: false,
            put: notInSortableList
        },
        sort: false,
        animation: 150,
        onAdd: addTagToItem
    });
});

document.querySelectorAll("[data-list='products']").forEach(function (sortableFilterEl) {
    Sortable.create(sortableFilterEl, {
        sort: true,
        animation: 150,
        handle: '.box-header',
        draggable: '[data-item="product"]',
        onSort: sortItem,
        onStart: function () {
            document.querySelectorAll("[data-change='collapse']").forEach(function (collapseEl) {
                $(collapseEl).collapse('hide');
            });
        },
        onEnd: function () {
            document.querySelectorAll("[data-change='collapse']").forEach(function (collapseEl) {
                $(collapseEl).collapse('show');
            });
        }
    });
});

document.querySelectorAll("[data-list='filters']").forEach(function (sortableFilterEl) {
    Sortable.create(sortableFilterEl, {
        sort: true,
        animation: 150,
        handle: '.box-header',
        draggable: '[data-item="filter"]',
        onSort: sortItem
    });
});

document.querySelectorAll("[data-list='variants']").forEach(function (sortableFilterEl) {
    Sortable.create(sortableFilterEl, {
        sort: true,
        animation: 150,
        draggable: '[data-item="variant"]',
        onSort: sortItem
    });
});

document.querySelectorAll("[data-item='tag']").forEach(function (tagEl) {
    tagEl.addEventListener("mouseover", function (event) {
        var tagId = event.target.dataset.tagId
        document.querySelectorAll("[data-item='tag'][data-tag-id='" + tagId + "']").forEach(function (selectedTagEl) {
            selectedTagEl.classList.add('label-warning');
            selectedTagEl.classList.remove('label-primary');
        });
    })
    tagEl.addEventListener("mouseout", function (event) {
        var tagId = event.target.dataset.tagId
        document.querySelectorAll("[data-item='tag'][data-tag-id='" + tagId + "']").forEach(function (selectedTagEl) {
            selectedTagEl.classList.add('label-primary');
            selectedTagEl.classList.remove('label-warning');
        });
    })
});

document.querySelectorAll("[data-item='tag'] > button").forEach(function (tagButtonEl) {
    tagButtonEl.addEventListener("click", function (event) {
        event.preventDefault();
        var buttonEl = event.target;
        var parentEl = buttonEl.parentElement;
        var url = parentEl.dataset.removeUrl;
        fetch(url, {
            method: "POST",
            body: JSON.stringify({
                tagId: parentEl.dataset.tagId,
                model: parentEl.dataset.model,
                itemId: parentEl.dataset.itemId
            })
        })
            .then(function (response) {
                if (response.ok) {
                    return response.json();
                }
                throw Error("Fehler beim Speichern: " + response.statusText)
            })
            .then(function (json) {
                console.log(json);
                if (json.success) {
                    buttonEl.parentElement.remove();
                }
            })
            .catch(function (error) {
                console.error(error);
            })
    })
});

function notInSortableList(to, from, dragEl) {
    var newTagId = dragEl.dataset.tagId;
    for (var child of to.el.children) {
        var tagId = child.dataset.tagId
        if (tagId === newTagId) {
            return false;
        }
    }
    return true;
}

function addTagToItem(event) {
    var tagId = event.item.dataset.tagId;
    var itemId = event.to.dataset.itemId;
    var url = event.to.dataset.targetUrl;
    fetch(url, {
        method: "POST",
        body: JSON.stringify({tagId: tagId, itemId: itemId})
    })
        .then(function (response) {
            if (response.ok) {
                return response.json();
            }
            throw Error("Fehler beim Speichern: " + response.statusText)
        })
        .then(function (json) {
            console.log(json);
        })
        .catch(function (error) {
            console.error(error);
        })
}

function sortItem(event) {
    var childs = Array.prototype.slice.call(event.to.children)
    var items = childs.map(function (item) {
        return item.dataset.itemId
    })
    var url = event.to.dataset.sortUrl;
    fetch(url, {
        method: "POST",
        body: JSON.stringify({items: items})
    })
        .then(function (response) {
            if (response.ok) {
                return response.json();
            }
            throw Error("Fehler beim Speichern: " + response.statusText)
        })
        .then(function (json) {
            console.log(json);
        })
        .catch(function (error) {
            console.error(error);
        })
}
