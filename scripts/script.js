/**
 * name of CSS class that hides elements
 * @type {string}
 */
const hiddenClassName = "hidden";

/**
 * BASE URL of the API
 */
let apiUrl;

/**
 * URL for deleting items (without ID)
 */
let deleteItemUrl;

/**
 * form for editing list item
 */
let editForm;

// additional fields of editForm
let editItemName;
let editItemAmount;

/**
 * get URL for deleting selected item
 * @param id
 * @returns {*}
 */
function getDeleteApiUrlById(id)
{
    return deleteItemUrl + id;
}

/**
 * add delete item event to selected button
 * @param button
 */
function addDeleteItemEventToButton(button)
{
    const itemId = button.getAttribute("data-id");
    const deleteItemUrl = getDeleteApiUrlById(itemId);
    const itemRowId = `itemsRow${itemId}`;
    const itemRow = document.getElementById(itemRowId);

    // add 'click' event listener
    button.addEventListener('click', function ()
    {
        // AJAX request
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
                    alert("An error occurred while deleting this item");
                }
            })
            .catch(result =>
            {
                alert("can't reach API");
            });
    })
}

/**
 * add update item event to selected button
 * @param button
 */
function addUpdateItemEventToButton(button)
{
    const itemId = button.getAttribute("data-id");
    const editFormId = document.getElementById("editItemId");
    const itemRowId = `itemsRow${itemId}`;

    // add 'click' event listener
    button.addEventListener('click', function ()
    {
        editFormId.value = itemId;

        // display edit form
        if (editForm.classList.contains(hiddenClassName))
        {
            editForm.classList.remove(hiddenClassName);
        }

        // set additional form properties
        const itemName = document.getElementById(itemRowId + "name");
        const itemAmount = document.getElementById(itemRowId + "amount");
        editItemName.innerText = itemName.innerText;
        editItemAmount.innerText = itemAmount.innerText;
    });
}

/**
 * main function
 */
function main()
{
    // set URLs
    apiUrl = location.origin + location.pathname;
    deleteItemUrl = apiUrl + "?action=List/Item&id=";

    // set editForm variables to correct elements
    editForm = document.getElementById("editItemForm");
    editItemAmount = document.getElementById("editItemAmount");
    editItemName = document.getElementById("editItemName");

    // add event handling to 'delete' buttons
    const deleteButtons = document.getElementsByClassName("deleteItemButton");
    for (const button of deleteButtons)
    {
        addDeleteItemEventToButton(button);
    }

    // add event handling to 'update' buttons
    const updateButtons = document.getElementsByClassName("editItemButton");
    for (const button of updateButtons)
    {
        addUpdateItemEventToButton(button);
    }

    // add 'click' event listener to cancel editing button
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
