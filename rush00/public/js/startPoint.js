let center = document.getElementById("22");
center.setAttribute("class", "map-field map-open");

let px = $("#player").attr("data-x");
let py = $("#player").attr("data-y");

let up = document.getElementById((Number(px) - 1) + "" + py);
let down = document.getElementById((Number(px) + 1) + "" + py);
let right = document.getElementById(px + "" + (Number(py) + 1));
let left = document.getElementById(px + "" + (Number(py) - 1));

let upImg = document.createElement("img");
upImg.setAttribute("id", "up");
upImg.setAttribute("src", "js/up.png");
upImg.setAttribute("style", "position:relative; width:30%; height:30%; left: 35%; bottom: -35%;");

if (up)
    up.insertAdjacentElement("beforeend", upImg);

let leftImg = document.createElement("img");
leftImg.setAttribute("id", "left");
leftImg.setAttribute("src", "js/left.png");
leftImg.setAttribute("style", "position:relative; width:30%; height:30%; left: 35%; bottom: -35%;");

if (left)
    left.insertAdjacentElement("beforeend", leftImg);

let rightImg = document.createElement("img");
rightImg.setAttribute("id", "rigth");
rightImg.setAttribute("src", "js/right.png");
rightImg.setAttribute("style", "position:relative; width:30%; height:30%; left: 35%; bottom: -35%;");

if (right)
    right.insertAdjacentElement("beforeend", rightImg);

let downImg = document.createElement("img");
downImg.setAttribute("id", "down");
downImg.setAttribute("src", "js/down.png");
downImg.setAttribute("style", "position:relative; width:30%; height:30%; left: 35%; bottom: -35%;");

if (down)
    down.insertAdjacentElement("beforeend", downImg);



