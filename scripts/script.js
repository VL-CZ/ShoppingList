const apiUrl = "http://localhost/";
const hiddenClassName = "hidden";
let editForm;

function getDeleteApiUrlById(id)
{
    return apiUrl + "?action=Items/Item&id=" + id;
}

function addDeleteItemEventToButton(button)
{
    const itemId = button.getAttribute("data-id");
    const deleteItemUrl = getDeleteApiUrlById(itemId);
    button.addEventListener('click', function () {
        fetch(deleteItemUrl, {method: 'DELETE'})
            .then(response => response.json())
            .then(result => {
                alert(result.ok);
                location.reload();
            });
    })
}

function addUpdateItemEventToButton(button)
{
    const itemId = button.getAttribute("data-id");
    const editFormId = document.getElementById("editItemId");

    button.addEventListener('click', function () {
        editFormId.value = itemId;
        if (editForm.classList.contains(hiddenClassName))
        {
            editForm.classList.remove(hiddenClassName);
        }
    });
}

function main()
{
    editForm = document.getElementById("editItemForm");

    const deleteButtons = document.getElementsByClassName("deleteItemButton");
    for (const button of deleteButtons)
    {
        addDeleteItemEventToButton(button);
    }

    const updateButtons = document.getElementsByClassName("editItemButton");
    for (const button of updateButtons)
    {
        addUpdateItemEventToButton(button);
    }

    const cancelEditButton = document.getElementById("cancelEditButton");
    cancelEditButton.addEventListener('click', function () {
        editForm.classList.add(hiddenClassName);
    });
}

window.onload = function () {
    main();
};
