function tabSwap(tabName, contentSet) {
    $('#'+contentSet).children().hide();
    $('#'+tabName).show();
}