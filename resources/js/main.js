(function($)
{
    "use strict";


    /*----------  Menu sticky and scrol top  URL: http://www.bootstrapmb.com----------*/

    var windows = $(window);
    var screenSize = windows.width();
    var sticky = $(".header-sticky");
    var menubarTop = $(".menu-top");
    var headerTop = $(".header-top");


    windows.on("scroll", function()
    {
        var scroll = windows.scrollTop();


        if (screenSize >= 991)
        {
            if (scroll < 300)
            {
                sticky.removeClass("is-sticky");

                menubarTop.removeClass("d-none");
                menubarTop.addClass("d-block");
                headerTop.removeClass("d-none");
                headerTop.addClass("d-block");
            }
            else
            {
                sticky.addClass("is-sticky");
                menubarTop.addClass("d-none");
                menubarTop.removeClass("d-block");
                headerTop.addClass("d-none");
                headerTop.removeClass("d-block");
            }
        }


        //code for scroll top

        if (scroll >= 400)
        {
            $(".scroll-top").fadeIn();
        }
        else
        {
            $(".scroll-top").fadeOut();
        }

    });


    /*----------  Scroll to top  ----------*/

    $(".scroll-top").on("click", function()
    {
        $("html,body").animate({
            scrollTop: 0
        }, 2000);
    });


    /*----------  Currency and language dropdown  ----------*/

    $("#changeCurrency").on("click", function(event)
    {
        event.stopPropagation();
        $("#currencyList").slideToggle();
        $("#languageList").slideUp("slow");
        $("#accountList").slideUp("slow");
    });

    $("#changeLanguage").on("click", function(event)
    {
        event.stopPropagation();
        $("#languageList").slideToggle();
        $("#currencyList").slideUp("slow");
        $("#accountList").slideUp("slow");
    });

    $("#changeAccount").on("click", function(event)
    {
        event.stopPropagation();
        $("#accountList").slideToggle();
        $("#currencyList").slideUp("slow");
        $("#languageList").slideUp("slow");
    });

    $("body:not(#changeLanguage)").on("click", function()
    {
        $("#languageList").slideUp("slow");
    });
    $("body:not(#changeCurrency)").on("click", function()
    {
        $("#currencyList").slideUp("slow");
    });
    $("body:not(#changeAccount)").on("click", function()
    {
        $("#accountList").slideUp("slow");
    });

    /*----------  cart minibox toggle  ----------*/
    $("#cart-floating-box").on("flush", function()
    {
        $.get("/api/basket/index", function(response)
        {
            $("#cart-num").text(0);
            if (response.code == 1)
            {
                if (response.data.list.length == 0)
				{
					$(".cart-items").replaceWith($("#basket_empty").html());
					$(".cart-calculation").remove();
				}
				else
				{
					$("#cart-floating-box .cart-items").empty();
					var reg = new RegExp("\\[([^\\[\\]]*?)\\]", "igm");
					var quantity = 0;
					$.each(response.data.list, function(index, value)
					{
						quantity += value.quantity;

						var tpl = $($("#basket_item_tpl").html().replace(reg, function(node, key)
						{
							return value[key];
						}));

						tpl.find(".remove-item").on("click", function()
						{
							$.get("/api/basket/delete", { basketItemId: $(this).data("id") }, function(response)
							{
								if (response.code == 1)
								{
									tpl.remove();
									var quantity = parseInt(tpl.find(".quantity").html().replace(" ", "").replace("x", ""));
									$("#cart-num").html(parseInt($("#cart-num").html()) - quantity);

									$("#basket_total_price").html(parseFloat(parseFloat($("#basket_total_price").html()) - parseFloat(tpl.find(".format_discount_price").data("price"))).toFixed(2));

									if ($(".cart-items").children().length == 0)
									{
										$("#cart-floating-box").html($("#basket_empty").html());
									}
								}
							});
							return false;
						});

						$("#cart-floating-box .cart-items").append(tpl);
					});

					$("#cart-num").text(quantity);

					$("#basket_total_price").html(response.data.total);
				}
			}
		});
	});

	$("#cart-icon").on("click", function(event)
	{
		event.stopPropagation();
		$("#cart-floating-box").slideToggle();
	});

	//页面加载的时候加载购物车
	$("#cart-floating-box").trigger("flush");

    $("body:not(#cart-icon)").on("click", function()
    {
        $("#cart-floating-box").slideUp("slow");
    });


    /*----------  Category menu  ----------*/


    /*-- Variables --*/
    var categoryToggleWrap = $(".category-toggle-wrap");
    var categoryToggle = $(".category-toggle");
    var categoryMenu = $(".category-menu");
    var categoryMenu2 = $(".category-menu-2");
    var categoryMenu3 = $(".category-menu-3");


    /*
     *  Category Menu Default Close for Mobile & Tablet Device
     *  And Open for Desktop Device and Above
     */
    function categoryMenuToggle()
    {
        var screenSize = windows.width();
        if (screenSize >= 0)
        {
            categoryMenu.slideUp();
        }
        else
        {
            categoryMenu.slideDown();
        }

        if (screenSize <= 991)
        {
            categoryMenu2.slideUp();
            categoryMenu3.slideUp();
        }
        else
        {
            categoryMenu2.slideDown();
            categoryMenu3.slideDown();
        }


    }

    /*-- Category Menu Toggles --*/
    function categorySubMenuToggle()
    {
        var screenSize = windows.width();
        if (screenSize <= 991)
        {
            $(".category-menu .menu-item-has-children > a").prepend("<i class=\"expand menu-expand\"></i>");
            $(".category-menu .menu-item-has-children ul").slideUp();
            //        $('.category-menu .menu-item-has-children i').on('click', function(e){
            //            e.preventDefault();
            //            $(this).toggleClass('expand');
            //            $(this).siblings('ul').css('transition', 'none').slideToggle();
            //        })
        }
        else
        {
            $(".category-menu .menu-item-has-children > a i").remove();
            $(".category-menu .menu-item-has-children ul").slideDown();
        }
    }

    categoryMenuToggle();
    windows.resize(categoryMenuToggle);
    categorySubMenuToggle();
    windows.resize(categorySubMenuToggle);

    categoryToggle.on("click", function()
    {
        categoryMenu.slideToggle();
    });

    /*-- Category Sub Menu --*/
    $(".category-menu").on("click", "li a, li a .menu-expand", function(e)
    {
        var $a = $(this).hasClass("menu-expand") ? $(this).parent() : $(this);
        if ($a.parent().hasClass("menu-item-has-children"))
        {
            if ($a.attr("href") === "#" || $(this).hasClass("menu-expand"))
            {
                if ($a.siblings("ul:visible").length > 0)
                {
                    $a.siblings("ul").slideUp();
                }
                else
                {
                    $(this).parents("li").siblings("li").find("ul:visible").slideUp();
                    $a.siblings("ul").slideDown();
                }
            }
        }
        if ($(this).hasClass("menu-expand") || $a.attr("href") === "#")
        {
            e.preventDefault();
            return false;
        }
    });

    /*-- Sidebar Category --*/
    var categoryChildren = $(".sidebar-category li .children");

    categoryChildren.slideUp();
    categoryChildren.parents("li").addClass("has-children");

    $(".sidebar-category").on("click", "li.has-children > a", function(e)
    {

        if ($(this).parent().hasClass("has-children"))
        {
            if ($(this).siblings("ul:visible").length > 0)
            {
                $(this).siblings("ul").slideUp();
            }
            else
            {
                $(this).parents("li").siblings("li").find("ul:visible").slideUp();
                $(this).siblings("ul").slideDown();
            }
        }
        if ($(this).attr("href") === "#")
        {
            e.preventDefault();
            return false;
        }
    });


    /*----------  Category more toggle  ----------*/

    $(".category-menu li.hidden").hide();
    $("#more-btn").on("click", function(e)
    {

        e.preventDefault();
        $(".category-menu li.hidden").toggle(500);
        var htmlAfter = "<span class=\"lnr lnr-circle-minus\"></span> Less Categories";
        var htmlBefore = "<span class=\"lnr lnr-plus-circle\"></span> More Categories";


        if ($(this).html() == htmlBefore)
        {
            $(this).html(htmlAfter);
        }
        else
        {
            $(this).html(htmlBefore);
        }
    });


    /*----------  Mean menu active  ----------*/

    var mainMenuNav = $(".main-menu nav");
    mainMenuNav.meanmenu({
        meanScreenWidth: "991",
        meanMenuContainer: ".mobile-menu",
        meanMenuClose: "<span class=\"menu-close\"></span>",
        meanMenuOpen: "<span class=\"menu-bar\"></span>",
        meanRevealPosition: "right",
        meanMenuCloseSize: "0"
    });


    /*----------  Mailchimp  ----------*/
    $("#mc-form").ajaxChimp({
        language: "en",
        callback: mailChimpResponse,
        // ADD YOUR MAILCHIMP URL BELOW HERE!
        url: "http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef"

    });

    function mailChimpResponse(resp)
    {

        if (resp.result === "success")
        {
            $(".mailchimp-success").html("" + resp.msg).fadeIn(900);
            $(".mailchimp-error").fadeOut(400);

        }
        else if (resp.result === "error")
        {
            $(".mailchimp-error").html("" + resp.msg).fadeIn(900);
        }
    }


    /*----------  Quantity Counter  ----------*/

    $(".pro-qty").append("<a href=\"#\" class=\"inc qty-btn\"><i class=\"fa fa-angle-up\"></i></a>");
    $(".pro-qty").append("<a href=\"#\" class= \"dec qty-btn\"><i class=\"fa fa-angle-down\"></i></a>");
    $(".qty-btn").on("click", function(e)
    {
        e.preventDefault();
        var $button = $(this);
        var oldValue = $button.parent().find("input").val();
        if ($button.hasClass("inc"))
        {
            var max = $(this).parents(".pro-qty").find("input").attr("max");
            if (max != undefined)
            {
                if (parseFloat(oldValue) + 1 <= max)
                {
                    var newVal = parseFloat(oldValue) + 1;
                }
            }
            else
            {
                var newVal = parseFloat(oldValue) + 1;
            }
        }
        else
        {
            var min = $(this).parents(".pro-qty").find("input").attr("min");
            if (min == undefined)
            {
                min = 1;
            }

            // Don't allow decrementing below zero
            if (oldValue > min)
            {
                var newVal = parseFloat(oldValue) - 1;
            }
            else
            {
                newVal = min;
            }
        }
        $button.parent().find("input").val(newVal).trigger("change");
    });


    /*----------  Nice Select  ----------*/

    $(".nice-select").niceSelect();

    /*----------  Payment method select  ----------*/

    $("[name=\"payment-method\"]").on("click", function()
    {

        var $value = $(this).attr("value");

        $(".single-method p").slideUp();
        $("[data-method=\"" + $value + "\"]").slideDown();

	});


    $(".create-address").on("click", function()
    {
        if ($("#shipping-form").is(":visible"))
        {
            $("#shipping-form").slideUp();
        }
        else
        {
            $("#shipping-form").slideDown();
            $(".create-address").parent().fadeOut();
        }
        return false;
    });


    $(".address-list").on("click", ".address-select", function()
    {
        $(this).parents(".address").siblings().removeClass("selected");
        $(this).parents(".address").addClass("selected");
        return false;
    });


    /*----------  Slider one active  ----------*/


    var heroSlider = $(".hero-slider-one");
    heroSlider.slick({
        arrows: true,
        autoplay: true,
        autoplaySpeed: 8000,
        dots: true,
        pauseOnFocus: false,
        pauseOnHover: false,
        fade: true,
        infinite: true,
        slidesToShow: 1,
        prevArrow: "<button type=\"button\" class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
        nextArrow: "<button type=\"button\" class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>"
    });


    /*----------  top selling product slider  ----------*/

    var topSellingSlider = $(".top-selling-product-slider-container, .related-product-slider-container");
    topSellingSlider.slick({
        centerMode: false,
        arrows: true,
        autoplay: false,
        dots: false,
        infinite: true,
        slidesToShow: 4,
        prevArrow: "<button type=\"button\" class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
        nextArrow: "<button type=\"button\" class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
        responsive: [{
            breakpoint: 1499,
            settings: {
                slidesToShow: 4
            }
        },
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 767,
				settings: {
                    slidesToShow: 2
                }
			},
			{
				breakpoint: 575,
				settings: {
                    slidesToShow: 2
                }
			},
			{
				breakpoint: 479,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });


    /*----------  Blog post slider  ----------*/

    var blogPostSlider = $(".blog-post-slider-container");
    blogPostSlider.slick({
        arrows: true,
        autoplay: false,
        dots: false,
        infinite: true,
        slidesToShow: 3,
        prevArrow: "<button type=\"button\" class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
        nextArrow: "<button type=\"button\" class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
        responsive: [{
            breakpoint: 1499,
            settings: {
                slidesToShow: 3
            }
        },
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2
                }
			},
			{
				breakpoint: 767,
				settings: {
                    slidesToShow: 2
                }
			},
			{
				breakpoint: 575,
				settings: {
                    slidesToShow: 2
                }
			},
			{
				breakpoint: 479,
				settings: {
                    slidesToShow: 1
                }
            }
        ]
    });


    /*----------  Multirow slider   ----------*/


    $(".double-row-slider-container").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: true,
            arrows: true,
            dots: false,
            slidesToShow: 5,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 5
                }
            },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });

    /*----------  Multirow slider   ----------*/


    $(".double-row-slider-container-home-3").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: true,
            arrows: true,
            dots: false,
            slidesToShow: 4,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 4
                }
            },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });


    /*----------  Single row slider  ----------*/

    $(".deal-slider-container").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: true,
            arrows: true,
            dots: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 2
                }
            },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });


    /*----------  activate countdown  ----------*/

    $("[data-countdown]").each(function()
    {
        var $this = $(this),
            finalDate = $(this).data("countdown");
        $this.countdown(finalDate, function(event)
        {
            $this.html(event.strftime("<div class=\"single-countdown\"><span class=\"single-countdown-time\">%D</span><span class=\"single-countdown-text\">Days</span></div><div class=\"single-countdown\"><span class=\"single-countdown-time\">%H</span><span class=\"single-countdown-text\">Hours</span></div><div class=\"single-countdown\"><span class=\"single-countdown-time\">%M</span><span class=\"single-countdown-text\">Mins</span></div><div class=\"single-countdown\"><span class=\"single-countdown-time\">%S</span><span class=\"single-countdown-text\">Secs</span></div>"));
        });
    });


    /*----------  popular product slider  ----------*/


    $(".popular-product-slider").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: false,
            arrows: true,
            dots: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 2
                }
            },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });

    /*----------  popular product slider  ----------*/


    $(".popular-product-slider-one-column").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: false,
            arrows: true,
            dots: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 1
                }
            },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });


    /*----------  instagram slider  ----------*/


    $(".instagram-slider-container").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: false,
            arrows: true,
            dots: false,
            slidesToShow: 5,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [
                {
                    breakpoint: 1499,
                    settings: {
                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });


    /*----------  image popup  ----------*/

    var imagePopup = $(".big-image-popup");
    imagePopup.magnificPopup({
        type: "image",
        gallery: {
            enabled: true
        }
    });


    /*----------  quickview image gallery active  ----------*/

    // $(".quickview-small-image-slider").slick({
    //     prevArrow: "<i class=\"fa fa-angle-up slick-prev\"></i>",
    //     nextArrow: "<i class=\"fa fa-angle-down slick-next\"></i>",
    //     slidesToShow: 3,
    //     vertical: true,
    //     responsive: [
    //         {
    //             breakpoint: 1499,
    //             settings: {
    //                 slidesToShow: 3
    //             }
    //         },
    //         {
    // 			breakpoint: 1199,
    // 			settings: {
    //                 slidesToShow: 3
    //             }
    // 		},
    // 		{
    // 			breakpoint: 991,
    // 			settings: {
    //                 slidesToShow: 3
    //             }
    // 		},
    // 		{
    // 			breakpoint: 767,
    // 			settings: {
    //                 slidesToShow: 3
    //             }
    // 		},
    // 		{
    // 			breakpoint: 575,
    // 			settings: {
    //                 slidesToShow: 3
    //             }
    //         },
    //         {
    //             breakpoint: 479,
    //             settings: {
    //                 slidesToShow: 3
    //             }
    //         }
    //     ]
    // });
    //
    // $(".modal").on("shown.bs.modal", function(e)
    // {
    //     $(".small-image-slider").resize();
    //     $(".small-image-slider").slick("setPosition");
    //
    // });

    $(".small-image-slider").on("click", "a", function(e)
    {
        e.preventDefault();

        var $thisParent = $(this).closest(".product-image-slider");
        var $href = $(this).attr("href");
        $thisParent.find(".small-image-slider a").removeClass("active");
        $(this).addClass("active");

        $thisParent.find(".product-large-image-list .tab-pane").removeClass("active show");
        $thisParent.find(".product-large-image-list " + $href).addClass("active show");

    });


    /*----------  Product tabstyle image gallery active  ----------*/

    $(".pts-small-image-slider").slick({
        prevArrow: "<i class=\"fa fa-angle-up slick-prev\"></i>",
        nextArrow: "<i class=\"fa fa-angle-down slick-next\"></i>",
        slidesToShow: 3,
        vertical: true,
        responsive: [
            {
                breakpoint: 1499,
                settings: {
                    slidesToShow: 3
                }
            },
            {
				breakpoint: 1199,
				settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 991,
				settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 767,
				settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 575,
				settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 479,
				settings: {
                    prevArrow: "<i class=\"fa fa-angle-left slick-prev\"></i>",
                    nextArrow: "<i class=\"fa fa-angle-right slick-next\"></i>",
                    vertical: false,
                    slidesToShow: 2
                }
            }
        ]
    });


    /*----------  product tabstyle three image gallery  ----------*/

    $(".pts3-small-image-slider").slick({
        prevArrow: "<i class=\"fa fa-angle-left slick-prev\"></i>",
        nextArrow: "<i class=\"fa fa-angle-right slick-next\"></i>",
        slidesToShow: 3,
        responsive: [
            {
                breakpoint: 1499,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 1199,
				settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 991,
				settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 767,
				settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 575,
				settings: {
                    slidesToShow: 3
                }
			},
			{
				breakpoint: 479,
				settings: {
                    slidesToShow: 3
                }
            }
        ]
    });


    /*----------  sticky sidebar   ----------*/


    $(".single-product-details-sticky").stickySidebar({
        topSpacing: 90,
        bottomSpacing: -90,
        minWidth: 768
    });


    /*----------  single product image slider  ----------*/


    $(".single-product-image-gallery-slider").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: true,
            arrows: true,
            dots: false,
            slidesToShow: 3,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [
                {
                    breakpoint: 1499,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });


    /*----------  product view mode  ----------*/

    $(".view-mode-icons a").on("click", function(e)
    {
        e.preventDefault();

        var shopProductWrap = $(".shop-product-wrap");
        var viewMode = $(this).data("target");

        $(".view-mode-icons a").removeClass("active");
        $(this).addClass("active");
        shopProductWrap.removeClass("grid list").addClass(viewMode);
    });


    /*----------  sidebar category dropdown  ----------*/

    var sidebarCategoryParent = $(".single-sidebar-widget ul li.has-children");
    sidebarCategoryParent.append("<a href=\"#\" class=\"expand-icon\">+</a>");

    var expandIcon = $(".expand-icon");
    expandIcon.on("click", function(e)
    {
        e.preventDefault();
        $(this).prev("ul").slideToggle();
        var htmlAfter = "-";
        var htmlBefore = "+";


        if ($(this).html() == htmlBefore)
        {
            $(this).html(htmlAfter);
        }
        else
        {
            $(this).html(htmlBefore);
        }
    });


    /*----------  blog image gallery slider  ----------*/

    var blogPostSlider = $(".blog-image-gallery");
    blogPostSlider.slick({
        prevArrow: "<button type=\"button\" class=\"slick-prev\"><i class=\"fa fa-chevron-left\"></i></button>",
        nextArrow: "<button type=\"button\" class=\"slick-next\"><i class=\"fa fa-chevron-right\"></i></button>",
        arrows: true,
        autoplay: false,
        autoplaySpeed: 5000,
        dots: false,
        pauseOnFocus: false,
        pauseOnHover: false,
        infinite: true,
        slidesToShow: 1
    });


    /*----------  search overlay  ----------*/

    $("#search-overlay-active-button").on("click", function(e)
    {
        e.preventDefault();
        $("#search-overlay").fadeIn();
    });

    $("#search-overlay-close").on("click", function(e)
    {
        e.preventDefault();
        $("#search-overlay").fadeOut();
    });

    /*----------  Brand logo slider   ----------*/


    $(".brand-logo-slider-container").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: true,
            arrows: false,
            dots: false,
            slidesToShow: 6,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 6
                }
            },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });
    });

    /*----------  featured category slider   ----------*/


    $(".featured-category-container").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: true,
            arrows: true,
            dots: false,
            slidesToShow: 3,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 3
                }
            },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });

    /*----------  tab content slider   ----------*/


    $(".tab-content-slider-container").each(function()
    {
        var $this = $(this);
        var $row = $this.attr("data-row") ? parseInt($this.attr("data-row"), 10) : 1;
        $this.slick({
            infinite: true,
            arrows: false,
            dots: false,
            slidesToShow: 3,
            slidesToScroll: 1,
            rows: $row,
            prevArrow: "<button class=\"slick-prev\"><i class=\"lnr lnr-chevron-left\"></i></button>",
            nextArrow: "<button class=\"slick-next\"><i class=\"lnr lnr-chevron-right\"></i></button>",
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 3
                }
            },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });

    $("#languageList").on("click", "a", function()
    {
        $.get("/api/index/language", { id: $(this).data("id") }, function()
        {
            window.location.reload();
        });
        return false;
    });


})(jQuery);

var addBasket = function(variationId, quantity, args, callback)
{
	if ($("#cart-floating-box .cart-empty").length != 0)
	{
		$("#cart-floating-box").html($("#basket_not_empty").html());
	}

	quantity = parseInt(quantity);


	$.get("/api/basket/create", { variationId: variationId, quantity: quantity }, function(response)
	{
		if (response.code == 1)
		{
			$("#cart-num").text(parseInt($("#cart-num").text()) + quantity);


			var hasInBasket = false;
			$(".cart-items").find(".cart-float-single-item").each(function(key, value)
			{
				if ($(value).data("id") == variationId)
				{
					hasInBasket = true;

					//调整数量
					var quantity_div = $(value).find(".quantity");
					var old_quantity = parseInt(quantity_div.html().replace(" ", "").replace("x", ""));
					quantity_div.html((old_quantity + quantity) + " x");
					//调整金额
					$("#basket_total_price").html(parseFloat(parseFloat($("#basket_total_price").html()) + args.discount_price).toFixed(2));
					return false;
				}
			});

			if (!hasInBasket)
			{

				args.basketItemId = response.data.basketItemId;
				args.quantity = quantity;

				var reg = new RegExp("\\[([^\\[\\]]*?)\\]", "igm");
				var item = $($("#basket_item_tpl").html().replace(reg, function(node, key)
				{
					return args[key];
				}));

				item.find(".remove-item").on("click", function()
				{
					$.get("/api/basket/delete", { basketItemId: $(this).data("id") }, function(response)
					{
						if (response.code == 1)
						{
							item.remove();
							var quantity = parseInt(item.find(".quantity").html().replace(" ", "").replace("x", ""));
							$("#cart-num").html(parseInt($("#cart-num").html()) - quantity);

							//调整金额
							$("#basket_total_price").html(parseFloat(parseFloat($("#basket_total_price").html()) - parseFloat(item.find(".format_discount_price").data("price"))).toFixed(2));

							if ($(".cart-items").children().length == 0)
							{
								$("#cart-floating-box").html($("#basket_empty").html());
							}
						}
					});
                    return false;
                });

                $(".cart-items").append(item);
            }
        }

        callback(response.code);
    });
};

var add_to_wishlist = function(id, success, failed)
{
    $.get("/api/wishlist/create/" + id, function(response)
    {
        if (response.code == 1)
        {
            success();
        }
        else
        {
            failed();
        }
    });
};
