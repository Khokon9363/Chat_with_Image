<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('carousel/bootstrap.min.css')}}" type="text/css" rel="stylesheet" media="all">
    <link href="{{ asset('carousel/style.css')}}" type="text/css" rel="stylesheet" media="all">
    <link href="{{ asset('carousel/timeline.css')}}" type="text/css" rel="stylesheet" media="all">
    <!-- <link href="{{ asset('carousel/font-awesome.min.css')}}" rel="stylesheet"> -->
    <!-- <link href="//fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800" rel="stylesheet"> -->
</head>
<body>

<div class="cliptop-blog-wthree" id="coupons">
        <div class="container">
            <div class="title-sec-w3layouts_pvt">
                <span class="title-icon-wthree fa fa-hashtag" aria-hidden="true"></span>
                <h4 class="w3layouts_pvt-head text-white">latest Coupons</h4>
            </div>
            <div class="row blog pt-5 mt-md-3">
                <div class="col-md-12">
                    <div id="blogCarousel" class="carousel slide" data-ride="carousel" data-interval="2000">

                        <ol class="carousel-indicators">
                            <li data-target="#blogCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#blogCarousel" data-slide-to="1"></li>
                            <li data-target="#blogCarousel" data-slide-to="2"></li>
                        </ol>

                        <!-- Carousel items -->
                        <div class="carousel-inner">
@foreach($sliders as $key => $sliders)
                        <div class="carousel-item <?php if($key === 0){ echo 'active';} ?>">
                                <div class="d-flex team-agile-row">
                                    <div class="col-lg-4 col-md-6 w3-slide-grid">
                                        <h5><a href="#">Title {{ ++$key }}</a></h5>
                                        <div class="box20">
                                            <div class="box-content">
                                                <h3 class="title">CODE :- </h3>
                                                <h4 class="title">( Anything )</h4>
                                                <span class="post text-capitalize">Expired at : Date</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
@endforeach
                        </div>
                        <!--.carousel-inner-->
                    </div>
                    <!--.Carousel-->
                </div>
            </div>
        </div>
    </div>
    <!-- //carousel -->
<script src="{{ asset('carousel/jquery.js') }}"></script>
<!-- <script src="{{ asset('carousel/menu.js') }}"></script> -->
<script src="{{ asset('carousel/responsiveslides.min.js') }}"></script>
<script>
    $(function () {
        $("#slider3").responsiveSlides({
            auto: true,
            pager: true,
            nav: false,
            speed: 500,
            namespace: "callbacks",
            before: function () {
                $('.events').append("<li>before event fired.</li>");
            },
            after: function () {
                $('.events').append("<li>after event fired.</li>");
            }
        });
    });
</script>
<script src="{{ asset('carousel/jquery.picEyes.js') }}"></script>
<!-- <script src="{{ asset('carousel/script.js') }}"></script> -->
<script>
    $(function () {
        $('.demo li').picEyes();
    });
</script>
<!-- <script src="{{ asset('carousel/scrolling-nav.js') }}"></script> -->
<script src="{{ asset('carousel/timeline.js') }}"></script>
<script src="{{ asset('carousel/move-top.js') }}"></script>
<script src="{{ asset('carousel/easing.js') }}"></script>
<script>
    jQuery(document).ready(function ($) {
        $(".scroll").click(function (event) {
            event.preventDefault();
            $('html,body').animate({
                scrollTop: $(this.hash).offset().top
            }, 1000);
        });
    });
</script>
<script>
    $(document).ready(function () {
        /*
        var defaults = {
            containerID: 'toTop', // fading element id
            containerHoverID: 'toTopHover', // fading element hover id
            scrollSpeed: 1200,
            easingType: 'linear'
        };
        */
        $().UItoTop({
            easingType: 'easeOutQuart'
        });
    });
</script>
<script src="{{ asset('carousel/bootstrap.min.js') }}"></script>
<script src="{{ asset('carousel/SmoothScroll.min.js') }}"></script>
</body>
</html>