/**
 * Cavallian Bros. メインJavaScript
 * WordPress/jQuery対応版
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // =========================================
    // 年度自動更新
    // =========================================
    function updateCopyright() {
        const currentYear = new Date().getFullYear();
        $('#year').text(currentYear);
    }
    
    // =========================================
    // スライダー機能
    // =========================================
    function initSlider() {
        const $slides = $('.hero-slider .slide');
        if ($slides.length === 0) return;
        
        let currentSlide = 0;
        const totalSlides = $slides.length;
        
        // 最初のスライドをアクティブに
        $slides.eq(0).addClass('active');
        
        function nextSlide() {
            $slides.eq(currentSlide).removeClass('active');
            currentSlide = (currentSlide + 1) % totalSlides;
            $slides.eq(currentSlide).addClass('active');
        }
        
        // 5秒ごとにスライド切り替え
        if (totalSlides > 1) {
            setInterval(nextSlide, 5000);
        }
    }
    
    // =========================================
    // スムーススクロール
    // =========================================
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const href = $(this).attr('href');
            
            if (href === '#' || href === '#0') return;
            
            const $target = $(href);
            if ($target.length) {
                e.preventDefault();
                const headerHeight = $('.header').outerHeight() || 0;
                const targetPosition = $target.offset().top - headerHeight;
                
                $('html, body').animate({
                    scrollTop: targetPosition
                }, 600, 'swing');
            }
        });
    }
    
    // =========================================
    // ヘッダーのスクロール制御
    // =========================================
    function initHeaderScroll() {
        const $header = $('.header');
        if (!$header.length) return;
        
        let lastScrollTop = 0;
        
        function handleScroll() {
            const scrollTop = $(window).scrollTop();
            
            // スクロール時にヘッダーの背景を変更
            if (scrollTop > 50) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }
            
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }
        
        $(window).on('scroll', handleScroll);
        
        // 初回実行
        handleScroll();
    }
    
    // =========================================
    // フェードインアニメーション
    // =========================================
    function initFadeInAnimation() {
        const $fadeElements = $('.fade-in');
        
        if ($fadeElements.length === 0) return;
        
        function checkFade() {
            const windowBottom = $(window).scrollTop() + $(window).height();
            
            $fadeElements.each(function() {
                const $element = $(this);
                const elementTop = $element.offset().top;
                
                if (elementTop < windowBottom - 50) {
                    $element.addClass('fade-in-visible');
                }
            });
        }
        
        $(window).on('scroll', checkFade);
        checkFade(); // 初回実行
    }
    
    // =========================================
    // モバイルメニュー（改良版）
    // =========================================
    function initMobileMenu() {
        const $menuButton = $('.menu-button, .mobile-menu-toggle');
        const $mobileMenu = $('.mobile-slide-menu, #mobile-menu');
        const $body = $('body');
        
        if ($menuButton.length && $mobileMenu.length) {
            // メニューボタンクリック
            $menuButton.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                $(this).toggleClass('active');
                $mobileMenu.toggleClass('active');
                $body.toggleClass('menu-open');
            });
            
            // メニューリンククリックで閉じる
            $mobileMenu.find('a').on('click', function() {
                $menuButton.removeClass('active');
                $mobileMenu.removeClass('active');
                $body.removeClass('menu-open');
            });
            
            // 外側クリックで閉じる
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.menu-button, .mobile-menu-toggle, .mobile-slide-menu').length) {
                    $menuButton.removeClass('active');
                    $mobileMenu.removeClass('active');
                    $body.removeClass('menu-open');
                }
            });
            
            // ESCキーで閉じる
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $body.hasClass('menu-open')) {
                    $menuButton.removeClass('active');
                    $mobileMenu.removeClass('active');
                    $body.removeClass('menu-open');
                }
            });
// Shopアコーディオン処理（モバイル）
$('.mobile-menu-list .menu-item-has-children > a.accordion-trigger').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const $parent = $(this).parent('.menu-item-has-children');
    $parent.toggleClass('active');
    
    console.log('Accordion toggled:', $parent.hasClass('active'));
});

// サブメニュー内のリンクは通常動作
$('.mobile-sub-menu a').on('click', function(e) {
    e.stopPropagation();
    // ページ遷移は許可
});

console.log('Shop accordion initialized');
        }
    }
    
    // =========================================
    // 画像の遅延読み込み（パフォーマンス最適化）
    // =========================================
    function initLazyLoad() {
        const $images = $('img[data-src]');
        
        if ($images.length === 0) return;
        
        // IntersectionObserver対応チェック
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            $images.each(function() {
                imageObserver.observe(this);
            });
        } else {
            // IntersectionObserverがサポートされていない場合
            $images.each(function() {
                const $img = $(this);
                $img.attr('src', $img.data('src'));
                $img.removeAttr('data-src');
            });
        }
    }
    
    // =========================================
    // WooCommerce関連
    // =========================================
    function initWooCommerce() {
        // カートに商品追加時のアニメーション
        $('body').on('added_to_cart', function() {
            $('.cart-count').addClass('bounce');
            setTimeout(function() {
                $('.cart-count').removeClass('bounce');
            }, 1000);
        });
        
        // 商品画像のホバーエフェクト
        $('.products .product').on('mouseenter', function() {
            $(this).find('img').addClass('hover');
        }).on('mouseleave', function() {
            $(this).find('img').removeClass('hover');
        });
        
        // カート数の動的更新
        $(document.body).on('added_to_cart removed_from_cart', function() {
            if (typeof wc_cart_fragments_params !== 'undefined') {
                $.ajax({
                    url: wc_cart_fragments_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'get_cart_count'
                    },
                    success: function(response) {
                        if (response.count > 0) {
                            $('.cart-count').text(response.count).show();
                        } else {
                            $('.cart-count').hide();
                        }
                    }
                });
            }
        });
    }
    
    // =========================================
    // Contact Form 7 関連
    // =========================================
    function initContactForm() {
        // フォーム送信成功時
        document.addEventListener('wpcf7mailsent', function(event) {
            // 成功メッセージのアニメーション
            $('.wpcf7-response-output').addClass('success-animation');
            
            // 3秒後にフォームをリセット
            setTimeout(function() {
                if ($('.wpcf7-form')[0]) {
                    $('.wpcf7-form')[0].reset();
                }
            }, 3000);
        }, false);
        
        // バリデーションエラー時
        document.addEventListener('wpcf7invalid', function(event) {
            $('.wpcf7-not-valid').first().focus();
        }, false);
    }
    
    // =========================================
    // フォーム検証
    // =========================================
    function initFormValidation() {
        const $forms = $('.validate-form');
        
        $forms.each(function() {
            const $form = $(this);
            
            $form.on('submit', function(e) {
                let isValid = true;
                const $inputs = $form.find('[required]');
                
                $inputs.each(function() {
                    const $input = $(this);
                    if (!$input.val().trim()) {
                        $input.addClass('error');
                        isValid = false;
                    } else {
                        $input.removeClass('error');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    }
    
    // =========================================
    // ローディング処理
    // =========================================
    function initLoader() {
        $(window).on('load', function() {
            const $loader = $('.loader');
            if ($loader.length) {
                setTimeout(function() {
                    $loader.addClass('loaded').fadeOut(500);
                }, 500);
            }
        });
    }
    
    // =========================================
    // 初期化関数の実行
    // =========================================
    function init() {
        updateCopyright();
        initSlider();
        initSmoothScroll();
        initHeaderScroll();
        initFadeInAnimation();
        initMobileMenu();
        initLazyLoad();
        initWooCommerce();
        initContactForm();
        initFormValidation();
        initLoader();
    }
    
    // 初期化実行
    init();
    
    // =========================================
    // ウィンドウリサイズ時の処理
    // =========================================
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // リサイズ完了後の処理
            if ($(window).width() > 768) {
                // デスクトップ表示時
                $('.mobile-slide-menu').removeClass('active');
                $('.menu-button').removeClass('active');
                $('body').removeClass('menu-open');
            }
        }, 250);
    });
    
    // =========================================
    // ユーティリティ関数
    // =========================================
    
    // デバウンス関数（パフォーマンス最適化用）
    window.debounce = function(func, wait) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                func.apply(context, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };
    
    // スロットル関数（パフォーマンス最適化用）  
    window.throttle = function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(function() {
                    inThrottle = false;
                }, limit);
            }
        };
    };
    
});

// =========================================
// WordPress管理バー対応
// =========================================
jQuery(document).ready(function($) {
    // 管理バーが表示されている場合のヘッダー位置調整
    if ($('#wpadminbar').length) {
        const adminBarHeight = $('#wpadminbar').outerHeight();
        $('.header').css('top', adminBarHeight + 'px');
        
        // スムーススクロールの調整
        $('a[href^="#"]').on('click', function(e) {
            const href = $(this).attr('href');
            if (href !== '#' && href !== '#0') {
                const $target = $(href);
                if ($target.length) {
                    e.preventDefault();
                    const headerHeight = $('.header').outerHeight() || 0;
                    const targetPosition = $target.offset().top - headerHeight - adminBarHeight;
                    
                    $('html, body').animate({
                        scrollTop: targetPosition
                    }, 600);
                }
            }
        });
    }
});