<!-- Footer Skeleton Loader -->
<footer class="main-footer skeleton-loading" id="footer-skeleton">
    <div class="footer-social-newsletter">
        <div class="newsletter-inline">
            <div class="skeleton skeleton-title"></div>
            <div class="newsletter-form">
                <div class="skeleton skeleton-input"></div>
                <div class="skeleton skeleton-button"></div>
            </div>
        </div>
        <div class="social-inline">
            <div class="skeleton skeleton-text"></div>
            <div class="social-icons">
                @for($i = 0; $i < 5; $i++)
                    <div class="skeleton skeleton-icon"></div>
                @endfor
            </div>
        </div>
    </div>

    <div class="footer-content">
        @for($i = 0; $i < 3; $i++)
            <div class="footer-column">
                <div class="skeleton skeleton-footer-title"></div>
                @for($j = 0; $j < 5; $j++)
                    <div class="skeleton skeleton-link-text"></div>
                @endfor
            </div>
        @endfor
    </div>

    <div class="footer-bottom">
        <div class="skeleton skeleton-text"></div>
    </div>
</footer>
