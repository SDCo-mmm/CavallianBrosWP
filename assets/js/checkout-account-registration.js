/**
 * チェックアウトページ - アカウント作成機能拡張
 * Version 3.1 - 完全版（パスワード機能修復）
 */
jQuery(document).ready(function($) {
    const DEBUG = false; // デバッグモードOFF（本番用）
    
    console.log('Checkout Registration Script v3.1 loaded');
    
    let fieldsAdded = false;
    let lastPasswordFieldId = null;
    let usernameSaveInterval = null;
    
    // CSSスタイルを追加
    if (!$('#checkout-custom-styles').length) {
        $('head').append(`
            <style id="checkout-custom-styles">
                .checkout-floating-label {
                    position: relative;
                }
                
                .checkout-floating-label input {
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #ddd;
                    font-size: 16px;
                    transition: border-color 0.3s;
                }
                
                .checkout-floating-label label {
                    position: absolute;
                    left: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    font-size: 14px;
                    color: #666;
                    background: white;
                    padding: 0 4px;
                    transition: all 0.2s;
                    pointer-events: none;
                }
                
                .checkout-floating-label input:focus + label,
                .checkout-floating-label input:not(:placeholder-shown) + label {
                    top: -10px;
                    transform: translateY(0);
                    font-size: 12px;
                    color: #333;
                }
                
                .checkout-floating-label input:focus {
                    outline: none;
                    border-color: #333;
                }
            </style>
        `);
    }
    
    /**
     * セッションにユーザー名を保存
     */
    function saveUsernameToSession(username) {
        if (!username) return;
        
        $.ajax({
            url: checkout_account_params.ajax_url,
            type: 'POST',
            data: {
                action: 'save_checkout_username',
                username: username,
                nonce: checkout_account_params.save_username_nonce || checkout_account_params.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (DEBUG) console.log('✅ Username saved to session:', response.data.username);
                } else {
                    if (DEBUG) console.log('❌ Failed to save username:', response);
                }
            },
            error: function(xhr, status, error) {
                if (DEBUG) console.error('❌ Error saving username:', error);
            }
        });
    }
    
    /**
     * Store APIリクエストのインターセプト（改良版）
     */
    function interceptStoreAPI() {
        const originalFetch = window.fetch;
        
        window.fetch = function(...args) {
            const [url, options] = args;
            
            // チェックアウトAPIの場合
            if (url && url.includes('/wc/store/') && url.includes('/checkout')) {
                if (options && options.body) {
                    try {
                        let body = JSON.parse(options.body);
                        
                        // ユーザー名を取得
                        const usernameInput = document.getElementById('checkout_username');
                        const username = usernameInput ? usernameInput.value : '';
                        
                        if (username) {
                            // 複数の場所に設定（冗長性確保）
                            if (!body.billing_address) body.billing_address = {};
                            if (!body.shipping_address) body.shipping_address = {};
                            if (!body.extensions) body.extensions = {};
                            
                            // すべての可能な場所に追加
                            body.billing_address.username = username;
                            body.shipping_address.username = username;
                            body.extensions.username = username;
                            body.username = username; // トップレベルにも
                            
                            // customer_noteに一時的に保存（最後の手段）
                            if (!body.customer_note) {
                                body.customer_note = '';
                            }
                            body.customer_note = `[USERNAME:${username}]` + body.customer_note;
                            
                            options.body = JSON.stringify(body);
                            
                            if (DEBUG) {
                                console.log('📤 Checkout data intercepted:', {
                                    username: username,
                                    body: body
                                });
                            }
                            
                            // セッションにも保存
                            saveUsernameToSession(username);
                        }
                        
                        // パスワード確認も追加
                        const passwordConfirm = document.getElementById('checkout_password_confirm');
                        if (passwordConfirm && passwordConfirm.value) {
                            body.extensions.password_confirm = passwordConfirm.value;
                            options.body = JSON.stringify(body);
                        }
                        
                    } catch (e) {
                        if (DEBUG) console.error('Error modifying checkout data:', e);
                    }
                }
            }
            
            // 元のfetchを実行
            return originalFetch.apply(this, args).then(response => {
                // レスポンスもログ
                if (url && url.includes('/checkout') && DEBUG) {
                    response.clone().json().then(data => {
                        console.log('📥 Checkout response:', data);
                    }).catch(() => {});
                }
                return response;
            });
        };
        
        if (DEBUG) console.log('✅ Store API interceptor installed');
    }
    
    /**
     * カスタムフィールドを追加
     */
    function addCustomFields() {
        if (fieldsAdded) return;
        
        let $passwordField = $('input[type="password"][aria-label*="パスワード"], input[type="password"][aria-label*="password"]').first();
        
        if (!$passwordField.length) {
            $passwordField = $('input[type="password"][autocomplete="new-password"]').first();
        }
        
        if (!$passwordField.length) {
            $passwordField = $('input[type="password"]').filter(function() {
                return $(this).closest('.wc-block-checkout__form').length > 0;
            }).first();
        }
        
        if ($passwordField.length) {
            const currentId = $passwordField.attr('id');
            if (currentId === lastPasswordFieldId) return;
            lastPasswordFieldId = currentId;
            
            let $passwordContainer = $passwordField.closest('.wc-block-components-text-input');
            if (!$passwordContainer.length) {
                $passwordContainer = $passwordField.parent();
            }
            
            // ユーザーネームフィールド
            const usernameHTML = `
                <div class="wc-block-components-text-input checkout-username-field" style="margin-bottom: 1.5rem;">
                    <div class="checkout-floating-label">
                        <input 
                            type="text" 
                            id="checkout_username" 
                            name="username" 
                            class="wc-block-components-text-input__input"
                            required
                            autocomplete="username"
                            placeholder=" "
                        />
                        <label for="checkout_username">
                            ユーザー名 <span style="color: #c62828;">*</span>
                        </label>
                    </div>
                    <span class="username-feedback" style="display: block; margin-top: 8px; font-size: 13px; min-height: 20px;"></span>
                </div>
            `;
            
            // パスワード確認フィールド
            const passwordConfirmHTML = `
                <div class="wc-block-components-text-input checkout-password-confirm-field" style="margin-bottom: 1.5rem; margin-top: 1.5rem;">
                    <div class="checkout-floating-label" style="position: relative;">
                        <input 
                            type="password" 
                            id="checkout_password_confirm" 
                            name="password_confirm" 
                            class="wc-block-components-text-input__input"
                            style="padding-right: 40px;"
                            required
                            autocomplete="new-password"
                            placeholder=" "
                            data-password-field="confirm"
                        />
                        <label for="checkout_password_confirm">
                            パスワード（確認） <span style="color: #c62828;">*</span>
                        </label>
                        <button type="button" class="show-password-confirm" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px; z-index: 10;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <span class="password-match-feedback" style="display: block; margin-top: 8px; font-size: 13px; min-height: 20px;"></span>
                </div>
            `;
            
            // パスワード表示ボタン（元のフィールド用）
            if (!$passwordField.siblings('.show-password-original').length) {
                const showPasswordBtn = `
                    <button type="button" class="show-password-original" data-target-id="${currentId}" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px; z-index: 10;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                `;
                $passwordField.parent().css('position', 'relative');
                $passwordField.css('padding-right', '40px');
                $passwordField.after(showPasswordBtn);
                $passwordField.attr('data-password-field', 'original');
            }
            
            // フィールドを挿入
            $passwordContainer.before(usernameHTML);
            $passwordContainer.after(passwordConfirmHTML);
            
            fieldsAdded = true;
            if (DEBUG) console.log('✅ Custom fields added');
            
            setupEventHandlers();
            
            // ユーザー名の自動保存を開始
            startUsernameSaving();
        }
    }
    
    /**
     * ユーザー名を定期的にセッションに保存
     */
    function startUsernameSaving() {
        if (usernameSaveInterval) {
            clearInterval(usernameSaveInterval);
        }
        
        // 3秒ごとにユーザー名をセッションに保存
        usernameSaveInterval = setInterval(function() {
            const username = $('#checkout_username').val();
            if (username && username.length >= 3) {
                saveUsernameToSession(username);
            }
        }, 3000);
    }
    
    /**
     * パスワード一致チェック関数（グローバル）
     */
    window.checkPasswordMatch = function() {
        // 元のパスワードフィールドを取得（動的IDに対応）
        let $originalPassword = $('[data-password-field="original"]');
        
        if (!$originalPassword.length) {
            $originalPassword = $('.show-password-original').siblings('input').first();
        }
        
        if (!$originalPassword.length) {
            // IDパターンで検索
            $originalPassword = $('input[id^="textinput-"]').filter(function() {
                const type = $(this).attr('type');
                return (type === 'password' || type === 'text') && !$(this).is('[data-password-field="confirm"]');
            }).first();
        }
        
        const $confirmPassword = $('#checkout_password_confirm');
        const $feedback = $('.password-match-feedback');
        
        if (!$originalPassword.length || !$confirmPassword.length) {
            return;
        }
        
        const password = $originalPassword.val();
        const confirmPassword = $confirmPassword.val();
        
        if (!confirmPassword) {
            $feedback.text('');
            $confirmPassword.css('border-color', '#ddd');
            return;
        }
        
        if (!password) {
            $feedback
                .text('パスワードを先に入力してください')
                .css('color', '#666');
            $confirmPassword.css('border-color', '#ddd');
            return;
        }
        
        if (password === confirmPassword) {
            $feedback
                .text('✓ パスワードが一致しています')
                .css('color', '#28a745');
            $confirmPassword.css('border-color', '#28a745');
        } else {
            $feedback
                .text('✗ パスワードが一致しません')
                .css('color', '#c62828');
            $confirmPassword.css('border-color', '#c62828');
        }
    };
    
    /**
     * イベントハンドラーの設定
     */
    function setupEventHandlers() {
        // 1. ユーザーネーム重複チェック
        let usernameTimeout;
        $(document).off('input', '#checkout_username').on('input', '#checkout_username', function() {
            const username = $(this).val();
            const $feedback = $('.username-feedback');
            const $input = $(this);
            
            clearTimeout(usernameTimeout);
            
            // セッションに保存
            if (username.length >= 3) {
                saveUsernameToSession(username);
            }
            
            if (username.length === 0) {
                $feedback.text('').removeClass('checking available taken error');
                $input.css('border-color', '#ddd');
                return;
            }
            
            if (username.length < 3) {
                $feedback
                    .text('ユーザー名は3文字以上で入力してください')
                    .removeClass('checking available')
                    .addClass('error')
                    .css('color', '#c62828');
                $input.css('border-color', '#c62828');
                return;
            }
            
            $feedback
                .text('確認中...')
                .removeClass('available taken error')
                .addClass('checking')
                .css('color', '#666');
            
            usernameTimeout = setTimeout(function() {
                $.ajax({
                    url: checkout_account_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'check_username_availability',
                        username: username,
                        nonce: checkout_account_params.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $feedback
                                .text('✓ このユーザー名は利用可能です')
                                .removeClass('checking taken error')
                                .addClass('available')
                                .css('color', '#28a745');
                            $input.css('border-color', '#28a745');
                            
                            // 利用可能ならセッションに保存
                            saveUsernameToSession(username);
                        } else {
                            $feedback
                                .text('✗ ' + response.data.message)
                                .removeClass('checking available')
                                .addClass('taken')
                                .css('color', '#c62828');
                            $input.css('border-color', '#c62828');
                        }
                    },
                    error: function() {
                        $feedback
                            .text('エラーが発生しました')
                            .removeClass('checking available taken')
                            .addClass('error')
                            .css('color', '#c62828');
                    }
                });
            }, 500);
        });
        
        // 2. パスワード表示/非表示切り替え（元のパスワード）
        $(document).off('click', '.show-password-original').on('click', '.show-password-original', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const targetId = $btn.data('target-id');
            let $input = targetId ? $('#' + targetId) : $btn.siblings('input').first();
            
            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $btn.find('svg').html(`
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                `);
            } else {
                $input.attr('type', 'password');
                $btn.find('svg').html(`
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                `);
            }
            
            // パスワード変更後に再チェック
            setTimeout(window.checkPasswordMatch, 100);
        });
        
        // 3. パスワード表示/非表示切り替え（確認）
        $(document).off('click', '.show-password-confirm').on('click', '.show-password-confirm', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const $input = $('#checkout_password_confirm');
            
            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $btn.find('svg').html(`
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                `);
            } else {
                $input.attr('type', 'password');
                $btn.find('svg').html(`
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                `);
            }
        });
        
        // 4. パスワード一致チェックのイベント設定
        $(document).off('input', '#checkout_password_confirm').on('input', '#checkout_password_confirm', window.checkPasswordMatch);
        
        // 元のパスワードフィールドの入力時もチェック
        $(document).off('input.pwcheck', 'input[id^="textinput-"]').on('input.pwcheck', 'input[id^="textinput-"]', function() {
            const $this = $(this);
            // パスワード確認フィールドでない場合のみ
            if (!$this.is('[data-password-field="confirm"]')) {
                // 表示/非表示ボタンがある場合はパスワードフィールドと判定
                if ($this.siblings('.show-password-original').length || $this.parent().find('.show-password-original').length) {
                    window.checkPasswordMatch();
                }
            }
        });
    }
    
    // Store APIインターセプターをインストール
    interceptStoreAPI();
    
    // MutationObserverでDOMを監視
    const observer = new MutationObserver(function() {
        if ($('.wc-block-checkout__form').length || $('input[type="password"]').length) {
            addCustomFields();
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // 初期実行
    setTimeout(addCustomFields, 1000);
    
    // 定期チェック
    let checkCount = 0;
    const checkInterval = setInterval(function() {
        checkCount++;
        if (fieldsAdded || checkCount > 60) {
            clearInterval(checkInterval);
            return;
        }
        addCustomFields();
    }, 500);
});
