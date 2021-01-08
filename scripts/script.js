const apiUrl = "http://localhost/";
const deleteItemUrl = apiUrl + "?action=Items/Item&id=";
const hiddenClassName = "hidden";
let editForm;
let editItemName;
let editItemAmount;

function getDeleteApiUrlById(id)
{
    return deleteItemUrl + id;
}

function addDeleteItemEventToButton(button)
{
    const itemId = button.getAttribute("data-id");
    const deleteItemUrl = getDeleteApiUrlById(itemId);
    const itemRowId = `itemsRow${itemId}`;
    const itemRow = document.getElementById(itemRowId);

    button.addEventListener('click', function ()
    {
        fetch(deleteItemUrl, {method: 'DELETE'})
            .then(response => response.json())
            .then(result =>
            {
                if (result.ok)
                {
                    itemRow.remove();
                }
                else
                {
                    alert("An error occurred while deleting item");
                }
            })
            .catch(result =>
            {
                alert("can't reach API");
            });
    })
}

function addUpdateItemEventToButton(button)
{
    const itemId = button.getAttribute("data-id");
    const editFormId = document.getElementById("editItemId");
    const itemRowId = `itemsRow${itemId}`;

    button.addEventListener('click', function ()
    {
        editFormId.value = itemId;

        // display edit form
        if (editForm.classList.contains(hiddenClassName))
        {
            editForm.classList.remove(hiddenClassName);
        }

        // set additional properties
        const itemName = document.getElementById(itemRowId + "name");
        const itemAmount = document.getElementById(itemRowId + "amount");
        editItemName.innerText = itemName.innerText;
        editItemAmount.innerText = itemAmount.innerText;
    });
}

function main()
{
    editForm = document.getElementById("editItemForm");
    editItemAmount = document.getElementById("editItemAmount");
    editItemName = document.getElementById("editItemName");

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
    cancelEditButton.addEventListener('click', function ()
    {
        editForm.classList.add(hiddenClassName);
    });
}

window.onload = function ()
{
    main();
};
