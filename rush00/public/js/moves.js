function isCanMove(x, y, px, py) {
    if ((Math.abs(x - px) == 1 && Math.abs(y - py) == 0) ||
        (Math.abs(x - px) == 0 && Math.abs(y - py) == 1))
        return true;
    return false;
}

$(".map-link").click(function () {
        let x, y, playerx, playery;

        x = $(this).attr("data-x");
        y = $(this).attr("data-y");
        playerx = $("#player").attr("data-x");
        playery = $("#player").attr("data-y");

        if (isCanMove(x, y, playerx, playery) === false)
            return false;

});

