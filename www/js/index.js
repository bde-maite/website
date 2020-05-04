function drag() {
    document.getElementById("nav-drag").offsetWidth
}

function hideElementeUp() {
    document.getElementById("updates")
}
$("#close-navbar").click(function() {
    var e = document.getElementById("nav-drag");
    e.style.width = "0", e.style.visibility = "hidden", document.getElementById("box-2").style.width = "100%", trage.style.visibility = "visible"
}), $("#trage").click(function() {
    var e = document.getElementById("nav-drag"),
        n = document.getElementById("trage");
    e.style.visibility = "visible", e.style.display = "inline-block", e.style.width = "320px", n.style.visibility = "hidden"
}), $(document).ready(function() {
    $(window).scroll(function() {
        $(this).scrollTop() > 100 ? $(".mergisus").fadeIn() : $(".mergisus").fadeOut()
    }), $(".mergisus").click(function() {
        return $("html, body").animate({
            scrollTop: 0
        }, 600), !1
    })
}), window.onscroll = function() {
    myFunction()
}, $(window).resize(function() {
    checkPhone(), responsiveDesign()
}), window.onload = function() {
    checkPhone()
};
var navbar = document.getElementById("navbar"),
    sticky = navbar.offsetTop,
    w = window.innerWidth;

function myFunction() {
    //ancienne couleur (navbar.style.background) #2b2c30
    window.pageYOffset >= sticky && w >= 960 ? (navbar.classList.add("sticky"), navbar.style.background = "#32445c", navbar.style.margin = "0", navbar.style.padding = "20px") : (navbar.classList.remove("sticky"), navbar.style.background = "none", navbar.style.margin = "20px 0px", navbar.style.padding = "0px")
}

function checkWidth() {
    console.log(window.innerWidth)
}

function checkPhone() {
    var e = document.getElementById("open-nav"),
        n = document.getElementById("button-open"),
        t = document.getElementById("cacher"),
        i = document.getElementById("mobile-nav"),
        o = window.innerWidth,
        l = document.getElementById("navbar");
    o <= 960 ? (e.style.display = "block", n.style.display = "block !important", t.style.display = "none", l.classList.remove("sticky"), l.style.background = "none", l.style.margin = "20px 0px", l.style.padding = "0px") : (e.style.display = "none", n.style.display = "none !important", t.style.display = "inline-block", i.style.left = "-300px")
}

function responsiveDesign() {
}
$("#open-nav").on("click", function() {
    document.getElementById("mobile-nav").style.left = "0px"
}), $("#close-nav").on("click", function() {
    document.getElementById("mobile-nav").style.left = "-300px"
});


$('.show-modal').click(function() {
 var modalId = this.getAttribute('data-modal');
 var modal = $('#' + modalId);
 modal.fadeIn();
});

$('.close-modal').click(function() {
 var modalId = this.getAttribute('data-modal');
 var modal = $('#' + modalId);
 modal.fadeOut();
});
