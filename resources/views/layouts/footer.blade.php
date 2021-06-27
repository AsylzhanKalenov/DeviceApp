


</div><!--/row-->


</div>
<br  style="clear:both" />
<hr>

<footer>

    <p>&copy; Каталог оборудования 2012</p>
</footer>

</div><!--/.fluid-container-->


<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
{{--<script src="/js/jquery-1.4.2.min.js"></script>--}}
<script type="text/javascript" src="{{asset('fancybox/jquery.fancybox-1.3.4.pack.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('fancybox/jquery.fancybox-1.3.4.css')}}" media="screen" />
<script src="{{asset('js/jquery.autocomplete_new.js')}}"></script>

<script type="text/javascript">
    $(function(){
        $('a[rel=image]').fancybox();
        $('a.map').fancybox({
            'transitionIn'	: 'none',
            'transitionOut'	: 'none'
        });
    })

</script>
<script src="/bootstrap/js/bootstrap.js"></script>
<script>
    (function ($) {
        $(function(){
            // fix sub nav on scroll
            var $win = $(window),
                $nav = $('.navbar'),
                navHeight = $('.navbar').first().height(),
                navTop = 80,
                isFixed = 0;

            processScroll();

            $win.on('scroll', processScroll);

            function processScroll() {
                var i, scrollTop = $win.scrollTop();
                if (scrollTop >= navTop && !isFixed) {
                    isFixed = 1;
                    $nav.addClass('navbar-fixed-top');
                } else if (scrollTop <= navTop && isFixed) {
                    isFixed = 0;
                    $nav.removeClass('navbar-fixed-top');
                }
            }

        });

    })(window.jQuery);

    (function ($) {
        $(function(){

            $('.table-striped').addClass('table table-bordered table-hover');

            // fix sub nav on scroll
            var $win = $(window),
                $nav = $('.subnav'),
                navHeight = $('.subnav').first().height(),
                navTop = 146,
                isFixed = 0;

            processScroll();

            $win.on('scroll', processScroll);

            function processScroll() {
                var i, scrollTop = $win.scrollTop();
                if (scrollTop >= navTop && !isFixed) {
                    isFixed = 1;
                    $nav.addClass('subnav-fixed');
                } else if (scrollTop <= navTop && isFixed) {
                    isFixed = 0;
                    $nav.removeClass('subnav-fixed');
                }
            }

        });

    })(window.jQuery);
</script>
{{--<script src="/js/jquery-ui-1.8.24.custom.min.js"></script>--}}
<script src="{{asset('js/form.js')}}"></script>

<script src="{{asset('js/jquery.js')}}"></script>

</body>
</html>
