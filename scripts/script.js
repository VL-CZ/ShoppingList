const apiUrl = "http://localhost/";

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

function main()
{
    const deleteButtons = document.getElementsByClassName("deleteItemButton");
    for (const button of deleteButtons)
    {
        addDeleteItemEventToButton(button);
    }

}

window.onload = function () {
    main();
};
