<?php
/*
Plugin Name: QRland QR Code Generator
Description: Advanced QR Code Generator Plugin for WordPress. Create dynamic and static QR codes with custom styling, multiple languages support, and real-time preview. Features include gradient backgrounds, custom fonts, multi-language interface (7 languages), and advanced customization options.
Version: 2.0.0
Author: Terminalads
Author URI: https://terminalads.com
Plugin URI: https://qrland.ir
Text Domain: qrland-qr-generator
Domain Path: /languages
*/

// Global variables for dynamic buttons and default texts
global $qrcdr_dynamic_buttons, $qrcdr_default_dynamic_texts, $qrcdr_static_buttons;

// Function to read button names from main.js
function qrcdr_read_button_names_from_mainjs() {
    $mainjs_path = plugin_dir_path(__FILE__) . 'frontend/src/main.js';
    
    if (!file_exists($mainjs_path)) {
        return false;
    }
    
    $content = file_get_contents($mainjs_path);
    
    // Extract dynamic buttons
    preg_match_all("/text:\s*'dynamicButtons\.([^']+)'/", $content, $dynamic_matches);
    $dynamic_buttons = $dynamic_matches[1] ?? [];
    
    // Extract static buttons
    preg_match_all("/text:\s*'buttons\.([^']+)'/", $content, $static_matches);
    $static_buttons = $static_matches[1] ?? [];
    
    return [
        'dynamic' => $dynamic_buttons,
        'static' => $static_buttons
    ];
}

// Function to read button texts from messages.js
function qrcdr_read_button_texts_from_messages() {
    $messages_path = plugin_dir_path(__FILE__) . 'frontend/src/messages.js';
    
    if (!file_exists($messages_path)) {
        return false;
    }
    
    $content = file_get_contents($messages_path);
    
    // Extract dynamic button texts for all languages
    $languages = ['fa', 'en', 'ar', 'tr', 'ru', 'fr', 'de'];
    $dynamic_texts = [];
    $static_texts = [];
    
    foreach ($languages as $lang) {
        // Find dynamicButtons section for each language
        if (preg_match("/'{$lang}':\s*\{[^}]*dynamicButtons:\s*\{([^}]+)\}/s", $content, $matches)) {
            $dynamic_section = $matches[1];
            preg_match_all("/(\w+):\s*'([^']+)'/", $dynamic_section, $button_matches);
            
            for ($i = 0; $i < count($button_matches[1]); $i++) {
                $button = $button_matches[1][$i];
                $text = $button_matches[2][$i];
                $dynamic_texts[$lang][$button] = $text;
            }
        }
        
        // Find buttons section for each language
        if (preg_match("/'{$lang}':\s*\{[^}]*buttons:\s*\{([^}]+)\}/s", $content, $matches)) {
            $buttons_section = $matches[1];
            preg_match_all("/(\w+):\s*'([^']+)'/", $buttons_section, $button_matches);
            
            for ($i = 0; $i < count($button_matches[1]); $i++) {
                $button = $button_matches[1][$i];
                $text = $button_matches[2][$i];
                $static_texts[$lang][$button] = $text;
            }
        }
    }
    
    return [
        'dynamic' => $dynamic_texts,
        'static' => $static_texts
    ];
}

// Read button names from main.js
$button_names = qrcdr_read_button_names_from_mainjs();

// Read button texts from messages.js
$button_texts = qrcdr_read_button_texts_from_messages();

$qrcdr_dynamic_buttons = $button_names['dynamic'] ?? [
    'business_card', 'social_media', 'photo_gallery', 'video_gallery', 'link', 'website', 
    'online_menu', 'insurance', 'online_shop', 'booking', 'commercial_complex', 'product', 
    'product_label', 'gold_label', 'catalog'
];

$qrcdr_static_buttons = $button_names['static'] ?? [
    'link', 'text', 'email', 'location', 'tel', 'sms', 'whatsapp', 'instagram', 'zoom', 
    'wifi', 'vcard', 'event', 'paypal', 'bitcoin', 'ethereum'
];

// Store button texts globally
$qrcdr_dynamic_button_texts = $button_texts['dynamic'] ?? [];
$qrcdr_static_button_texts = $button_texts['static'] ?? [];

$qrcdr_default_dynamic_texts = [
    'fa' => [
        'business_card' => ['title' => 'کارت ویزیت', 'description' => 'ایجاد QR کد برای کارت ویزیت'],
        'social_media' => ['title' => 'شبکه‌های اجتماعی', 'description' => 'ایجاد QR کد برای شبکه‌های اجتماعی'],
        'photo_gallery' => ['title' => 'گالری عکس', 'description' => 'ایجاد QR کد برای گالری عکس'],
        'video_gallery' => ['title' => 'گالری ویدیو', 'description' => 'ایجاد QR کد برای گالری ویدیو'],
        'link' => ['title' => 'لینک', 'description' => 'ایجاد QR کد برای لینک'],
        'website' => ['title' => 'وب‌سایت', 'description' => 'ایجاد QR کد برای وب‌سایت'],
        'online_menu' => ['title' => 'منوی آنلاین', 'description' => 'ایجاد QR کد برای منوی آنلاین'],
        'insurance' => ['title' => 'بیمه', 'description' => 'ایجاد QR کد برای بیمه'],
        'online_shop' => ['title' => 'فروشگاه آنلاین', 'description' => 'ایجاد QR کد برای فروشگاه آنلاین'],
        'booking' => ['title' => 'رزرو', 'description' => 'ایجاد QR کد برای رزرو'],
        'commercial_complex' => ['title' => 'مجتمع تجاری', 'description' => 'ایجاد QR کد برای مجتمع تجاری'],
        'product' => ['title' => 'محصول', 'description' => 'ایجاد QR کد برای محصول'],
        'product_label' => ['title' => 'برچسب محصول', 'description' => 'ایجاد QR کد برای برچسب محصول'],
        'gold_label' => ['title' => 'برچسب طلا', 'description' => 'ایجاد QR کد برای برچسب طلا'],
        'catalog' => ['title' => 'کاتالوگ', 'description' => 'ایجاد QR کد برای کاتالوگ']
    ],
    'en' => [
        'business_card' => ['title' => 'Business Card', 'description' => 'Create QR code for business card'],
        'social_media' => ['title' => 'Social Media', 'description' => 'Create QR code for social media'],
        'photo_gallery' => ['title' => 'Photo Gallery', 'description' => 'Create QR code for photo gallery'],
        'video_gallery' => ['title' => 'Video Gallery', 'description' => 'Create QR code for video gallery'],
        'link' => ['title' => 'Link', 'description' => 'Create QR code for link'],
        'website' => ['title' => 'Website', 'description' => 'Create QR code for website'],
        'online_menu' => ['title' => 'Online Menu', 'description' => 'Create QR code for online menu'],
        'insurance' => ['title' => 'Insurance', 'description' => 'Create QR code for insurance'],
        'online_shop' => ['title' => 'Online Shop', 'description' => 'Create QR code for online shop'],
        'booking' => ['title' => 'Booking', 'description' => 'Create QR code for booking'],
        'commercial_complex' => ['title' => 'Commercial Complex', 'description' => 'Create QR code for commercial complex'],
        'product' => ['title' => 'Product', 'description' => 'Create QR code for product'],
        'product_label' => ['title' => 'Product Label', 'description' => 'Create QR code for product label'],
        'gold_label' => ['title' => 'Gold Label', 'description' => 'Create QR code for gold label'],
        'catalog' => ['title' => 'Catalog', 'description' => 'Create QR code for catalog']
    ],
    'ar' => [
        'business_card' => ['title' => 'بطاقة عمل', 'description' => 'إنشاء رمز QR لبطاقة العمل'],
        'social_media' => ['title' => 'وسائل التواصل الاجتماعي', 'description' => 'إنشاء رمز QR لوسائل التواصل الاجتماعي'],
        'photo_gallery' => ['title' => 'معرض الصور', 'description' => 'إنشاء رمز QR لمعرض الصور'],
        'video_gallery' => ['title' => 'معرض الفيديو', 'description' => 'إنشاء رمز QR لمعرض الفيديو'],
        'link' => ['title' => 'رابط', 'description' => 'إنشاء رمز QR للرابط'],
        'website' => ['title' => 'موقع ويب', 'description' => 'إنشاء رمز QR للموقع'],
        'online_menu' => ['title' => 'قائمة طعام على الإنترنت', 'description' => 'إنشاء رمز QR للقائمة'],
        'insurance' => ['title' => 'تأمين', 'description' => 'إنشاء رمز QR للتأمين'],
        'online_shop' => ['title' => 'متجر على الإنترنت', 'description' => 'إنشاء رمز QR للمتجر'],
        'booking' => ['title' => 'حجز', 'description' => 'إنشاء رمز QR للحجز'],
        'commercial_complex' => ['title' => 'مجمع تجاري', 'description' => 'إنشاء رمز QR للمجمع'],
        'product' => ['title' => 'منتج', 'description' => 'إنشاء رمز QR للمنتج'],
        'product_label' => ['title' => 'ملصق المنتج', 'description' => 'إنشاء رمز QR للملصق'],
        'gold_label' => ['title' => 'ملصق ذهبي', 'description' => 'إنشاء رمز QR للملصق الذهبي'],
        'catalog' => ['title' => 'كتالوج', 'description' => 'إنشاء رمز QR للكتالوج']
    ],
    'tr' => [
        'business_card' => ['title' => 'İş Kartı', 'description' => 'İş kartı için QR kod oluştur'],
        'social_media' => ['title' => 'Sosyal Medya', 'description' => 'Sosyal medya için QR kod oluştur'],
        'photo_gallery' => ['title' => 'Fotoğraf Galerisi', 'description' => 'Fotoğraf galerisi için QR kod oluştur'],
        'video_gallery' => ['title' => 'Video Galerisi', 'description' => 'Video galerisi için QR kod oluştur'],
        'link' => ['title' => 'Bağlantı', 'description' => 'Bağlantı için QR kod oluştur'],
        'website' => ['title' => 'Web Sitesi', 'description' => 'Web sitesi için QR kod oluştur'],
        'online_menu' => ['title' => 'Çevrimiçi Menü', 'description' => 'Çevrimiçi menü için QR kod oluştur'],
        'insurance' => ['title' => 'Sigorta', 'description' => 'Sigorta için QR kod oluştur'],
        'online_shop' => ['title' => 'Çevrimiçi Mağaza', 'description' => 'Çevrimiçi mağaza için QR kod oluştur'],
        'booking' => ['title' => 'Rezervasyon', 'description' => 'Rezervasyon için QR kod oluştur'],
        'commercial_complex' => ['title' => 'Ticari Kompleks', 'description' => 'Ticari kompleks için QR kod oluştur'],
        'product' => ['title' => 'Ürün', 'description' => 'Ürün için QR kod oluştur'],
        'product_label' => ['title' => 'Ürün Etiketi', 'description' => 'Ürün etiketi için QR kod oluştur'],
        'gold_label' => ['title' => 'Altın Etiket', 'description' => 'Altın etiket için QR kod oluştur'],
        'catalog' => ['title' => 'Katalog', 'description' => 'Katalog için QR kod oluştur']
    ],
    'ru' => [
        'business_card' => ['title' => 'Визитная карточка', 'description' => 'Создать QR-код для визитной карточки'],
        'social_media' => ['title' => 'Социальные сети', 'description' => 'Создать QR-код для социальных сетей'],
        'photo_gallery' => ['title' => 'Фотогалерея', 'description' => 'Создать QR-код для фотогалереи'],
        'video_gallery' => ['title' => 'Видеогалерея', 'description' => 'Создать QR-код для видеогалереи'],
        'link' => ['title' => 'Ссылка', 'description' => 'Создать QR-код для ссылки'],
        'website' => ['title' => 'Веб-сайт', 'description' => 'Создать QR-код для веб-сайта'],
        'online_menu' => ['title' => 'Онлайн меню', 'description' => 'Создать QR-код для онлайн меню'],
        'insurance' => ['title' => 'Страхование', 'description' => 'Создать QR-код для страхования'],
        'online_shop' => ['title' => 'Онлайн магазин', 'description' => 'Создать QR-код для онлайн магазина'],
        'booking' => ['title' => 'Бронирование', 'description' => 'Создать QR-код для бронирования'],
        'commercial_complex' => ['title' => 'Торговый комплекс', 'description' => 'Создать QR-код для торгового комплекса'],
        'product' => ['title' => 'Продукт', 'description' => 'Создать QR-код для продукта'],
        'product_label' => ['title' => 'Этикетка продукта', 'description' => 'Создать QR-код для этикетки продукта'],
        'gold_label' => ['title' => 'Золотая этикетка', 'description' => 'Создать QR-код для золотой этикетки'],
        'catalog' => ['title' => 'Каталог', 'description' => 'Создать QR-код для каталога']
    ],
    'fr' => [
        'business_card' => ['title' => 'Carte de visite', 'description' => 'Créer un code QR pour la carte de visite'],
        'social_media' => ['title' => 'Réseaux sociaux', 'description' => 'Créer un code QR pour les réseaux sociaux'],
        'photo_gallery' => ['title' => 'Galerie photo', 'description' => 'Créer un code QR pour la galerie photo'],
        'video_gallery' => ['title' => 'Galerie vidéo', 'description' => 'Créer un code QR pour la galerie vidéo'],
        'link' => ['title' => 'Lien', 'description' => 'Créer un code QR pour le lien'],
        'website' => ['title' => 'Site web', 'description' => 'Créer un code QR pour le site web'],
        'online_menu' => ['title' => 'Menu en ligne', 'description' => 'Créer un code QR pour le menu en ligne'],
        'insurance' => ['title' => 'Assurance', 'description' => 'Créer un code QR pour l\'assurance'],
        'online_shop' => ['title' => 'Boutique en ligne', 'description' => 'Créer un code QR pour la boutique en ligne'],
        'booking' => ['title' => 'Réservation', 'description' => 'Créer un code QR pour la réservation'],
        'commercial_complex' => ['title' => 'Complexe commercial', 'description' => 'Créer un code QR pour le complexe commercial'],
        'product' => ['title' => 'Produit', 'description' => 'Créer un code QR pour le produit'],
        'product_label' => ['title' => 'Étiquette produit', 'description' => 'Créer un code QR pour l\'étiquette produit'],
        'gold_label' => ['title' => 'Étiquette dorée', 'description' => 'Créer un code QR pour l\'étiquette dorée'],
        'catalog' => ['title' => 'Catalogue', 'description' => 'Créer un code QR pour le catalogue']
    ],
    'de' => [
        'business_card' => ['title' => 'Visitenkarte', 'description' => 'QR-Code für Visitenkarte erstellen'],
        'social_media' => ['title' => 'Soziale Medien', 'description' => 'QR-Code für soziale Medien erstellen'],
        'photo_gallery' => ['title' => 'Fotogalerie', 'description' => 'QR-Code für Fotogalerie erstellen'],
        'video_gallery' => ['title' => 'Videogalerie', 'description' => 'QR-Code für Videogalerie erstellen'],
        'link' => ['title' => 'Link', 'description' => 'QR-Code für Link erstellen'],
        'website' => ['title' => 'Website', 'description' => 'QR-Code für Website erstellen'],
        'online_menu' => ['title' => 'Online-Menü', 'description' => 'QR-Code für Online-Menü erstellen'],
        'insurance' => ['title' => 'Versicherung', 'description' => 'QR-Code für Versicherung erstellen'],
        'online_shop' => ['title' => 'Online-Shop', 'description' => 'QR-Code für Online-Shop erstellen'],
        'booking' => ['title' => 'Buchung', 'description' => 'QR-Code für Buchung erstellen'],
        'commercial_complex' => ['title' => 'Gewerbekomplex', 'description' => 'QR-Code für Gewerbekomplex erstellen'],
        'product' => ['title' => 'Produkt', 'description' => 'QR-Code für Produkt erstellen'],
        'product_label' => ['title' => 'Produktetikett', 'description' => 'QR-Code für Produktetikett erstellen'],
        'gold_label' => ['title' => 'Gold-Etikett', 'description' => 'QR-Code für Gold-Etikett erstellen'],
        'catalog' => ['title' => 'Katalog', 'description' => 'QR-Code für Katalog erstellen']
    ]
];



if (! defined('ABSPATH')) {
    exit;
}

// Include frontend functionality
require_once plugin_dir_path(__FILE__) . 'frontend/qrcdr-frontend.php';

// Include missing backend libraries
require_once plugin_dir_path(__FILE__) . 'lib/EasySVG.php';
require_once plugin_dir_path(__FILE__) . 'lib/countrycodes.php';





function qrcdr_shortcode(): string
{
    ob_start();

    ?>
     <div id="qrcdr-frontend-app" style="width: 100%; height: 600px;">
        <!-- Vue app will be mounted here -->
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('qrcdr', 'qrcdr_shortcode');

// Simple Vue test shortcode for debugging
function qrcdr_simple_vue_test_shortcode(): string
{
    ob_start();
    ?>
    <div id="simple-vue-test" style="width: 100%; height: 300px; border: 2px solid #ccc; padding: 20px; background: #f9f9f9;">
        <h3>Vue Test App</h3>
        <div v-if="message">
            <p>{{ message }}</p>
            <p>Loaded at: {{ timestamp }}</p>
        </div>
        <div v-else>
            <p>Vue app not loaded yet...</p>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('simple_vue_test', 'qrcdr_simple_vue_test_shortcode');

function qrcdr_enqueue_styles(): void
{
    global $post;
    if (is_object($post) && isset($post->post_content) && 
        (has_shortcode($post->post_content, 'qrcdr') || has_shortcode($post->post_content, 'qrcdr_alternate'))) {
        
        // Ensure jQuery is available when shortcode is used
        wp_enqueue_script('jquery');
    }
}
add_action('wp_head', 'qrcdr_enqueue_styles');

// Enqueue assets for Vue test shortcode
function qrcdr_enqueue_vue_test_assets(): void
{
    global $post;
    if (is_object($post) && isset($post->post_content) && 
        has_shortcode($post->post_content, 'simple_vue_test')) {
        
        // Enqueue Vue 2 from CDN for testing
        wp_enqueue_script('vue2-test', 'https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js', [], '2.6.14', true);
        
        // Enqueue our test script
        wp_enqueue_script('qrcdr-simple-test', plugin_dir_url(__FILE__) . 'assets/js/simple-main.js', ['vue2-test'], '1.0.0', true);
        
        // Enqueue test CSS
        wp_enqueue_style('qrcdr-test-css', plugin_dir_url(__FILE__) . 'assets/css/qrcdr-flags.css', [], '1.0.0');
        
        // Add debug info
        wp_localize_script('qrcdr-simple-test', 'qrcdr_test_debug', [
            'plugin_url' => plugin_dir_url(__FILE__),
            'timestamp' => current_time('timestamp')
        ]);
    }
}
add_action('wp_enqueue_scripts', 'qrcdr_enqueue_vue_test_assets');




function qrcdr_plugin_deactivation(): void
{
    // Clean up any temporary files if needed
    error_log('QRcdr plugin deactivated successfully.');
}

register_deactivation_hook(__FILE__, 'qrcdr_plugin_deactivation');
register_activation_hook(__FILE__, 'qrcdr_simple_activation');

function qrcdr_simple_activation(): void
{
    // Set default options
    add_option('qrcdr_locale', 'fa');
    add_option('qrcdr_bg_primary_color', '#950000');
    add_option('qrcdr_nav_link_color', '#0ff');
    
    // Set default visibility settings
    add_option('qrcdr_visible_title', '1');
    add_option('qrcdr_visible_text', '1');
    add_option('qrcdr_visible_nav_tab', '1');
    add_option('qrcdr_visible_static', '1');
    add_option('qrcdr_visible_dynamic', '1');
    
    // Set default appearance settings
    add_option('qrcdr_top_bg_color', '#fbe0e3');
    add_option('qrcdr_menu_bg_color', '#403199');
    add_option('qrcdr_active_menu_color', '#ab9fee');
}

register_activation_hook(__FILE__, 'qrcdr_simple_activation');


function hide_notices_qrcdr_settings(): void
{
    global $pagenow;

    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'QrCode_Settings') {
        remove_all_actions('admin_notices');
    }
}
add_action('admin_init', 'hide_notices_qrcdr_settings');

function plugin_qrcdr_menu(): void
{
    // Get WordPress locale for dynamic menu titles
    $locale = get_locale();
    $is_rtl = in_array($locale, ['fa_IR', 'ar', 'he_IL']);
    
    // Menu titles based on language
    $menu_titles = [
        'fa_IR' => [
            'menu_title' => 'کیو آر ساز',
            'page_title' => 'تنظیمات کیو آر ساز',
            'submenu_title' => 'تنظیمات'
        ],
        'ar' => [
            'menu_title' => 'مولد QR',
            'page_title' => 'إعدادات مولد QR',
            'submenu_title' => 'الإعدادات'
        ],
        'default' => [
            'menu_title' => 'QR Generator',
            'page_title' => 'QR Generator Settings',
            'submenu_title' => 'Settings'
        ]
    ];
    
    // Get appropriate titles
    $titles = $menu_titles[$locale] ?? $menu_titles['default'];
    
    add_menu_page(
        $titles['page_title'],
        $titles['menu_title'],
        'manage_options',
        'QrCode_Settings',
        'QrCode_Settings',
        plugins_url('assets/images/qr-code.png', __FILE__),
        30
    );
    
    // Add submenu
    add_submenu_page(
        'QrCode_Settings',
        $titles['page_title'],
        $titles['submenu_title'],
        'manage_options',
        'QrCode_Settings'
    );
}
add_action('admin_menu', 'plugin_qrcdr_menu');

// Enqueue fonts based on language
add_action('admin_enqueue_scripts', 'qrcdr_enqueue_fonts');
function qrcdr_enqueue_fonts() {
    $current_lang = qrcdr_get_wp_language();
    
    if ($current_lang === 'fa') {
        wp_enqueue_style('vazir-font', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css');
    } elseif ($current_lang === 'ar') {
        wp_enqueue_style('cairo-font', 'https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap');
    }
}

// Function to get WordPress locale and determine language
function qrcdr_get_wp_language() {
    $locale = get_locale();
    $current_lang = 'en'; // default
    
    if (strpos($locale, 'fa_IR') === 0) {
        $current_lang = 'fa';
    } elseif (strpos($locale, 'ar') === 0) {
        $current_lang = 'ar';
    } elseif (strpos($locale, 'tr_TR') === 0) {
        $current_lang = 'tr';
    } elseif (strpos($locale, 'ru_RU') === 0) {
        $current_lang = 'ru';
    } elseif (strpos($locale, 'fr_FR') === 0) {
        $current_lang = 'fr';
    } elseif (strpos($locale, 'de_DE') === 0) {
        $current_lang = 'de';
    }
    
    return $current_lang;
}

// Function to check if current language is RTL
function qrcdr_is_rtl() {
    $locale = get_locale();
    return in_array($locale, ['fa_IR', 'ar', 'he_IL']);
}

// Function to get admin text based on language
function qrcdr_get_admin_text($key, $lang = null) {
    if ($lang === null) {
        $lang = qrcdr_get_wp_language();
    }
    
    $admin_texts = [
        'fa' => [
            'page_title' => 'تنظیمات کیو آر ساز',
            'general_tab' => 'تنظیمات عمومی',
            'static_tab' => 'تنظیمات استاتیک',
            'dynamic_tab' => 'تنظیمات دینامیک',
            'messages_tab' => 'مدیریت متن‌ها',
            'save_settings' => 'ذخیره تنظیمات',
            'settings_saved' => 'تنظیمات با موفقیت ذخیره شدند!',
            'security_error' => 'خطای امنیتی! لطفاً دوباره تلاش کنید.',
            'files_updated' => 'فایل‌ها با موفقیت بروزرسانی شدند!',
                    'settings_reset' => 'تمام تنظیمات با موفقیت به مقادیر پیش‌فرض بازگردانده شدند.',
        'force_update_files' => 'بروزرسانی اجباری فایل‌ها',
        'confirm_force_update' => 'آیا مطمئن هستید؟ این عمل تمام فایل‌ها را دوباره کپی می‌کند.',
            'general_settings' => 'تنظیمات عمومی',
            'static_settings' => 'تنظیمات استاتیک',
            'dynamic_settings' => 'تنظیمات دینامیک',
            'message_settings' => 'مدیریت متن‌ها',
            'visibility_settings' => 'تنظیمات نمایش',
            'tag_settings' => 'تنظیمات تگ‌ها',
            'font_settings' => 'تنظیمات فونت',
            'background_settings' => 'تنظیمات پس‌زمینه',
            'size_settings' => 'تنظیمات اندازه',
            'appearance_settings' => 'تنظیمات ظاهری'
        ],
        'en' => [
            'page_title' => 'QR Generator Settings',
            'general_tab' => 'General Settings',
            'static_tab' => 'Static Settings',
            'dynamic_tab' => 'Dynamic Settings',
            'messages_tab' => 'Message Management',
            'save_settings' => 'Save Settings',
            'settings_saved' => 'Settings saved successfully!',
            'security_error' => 'Security error! Please try again.',
            'files_updated' => 'Files updated successfully!',
                            'settings_reset' => 'All settings have been successfully reset to their default values.',
        'force_update_files' => 'Force Update Files',
        'confirm_force_update' => 'Are you sure? This action will copy all files again.',
            'general_settings' => 'General Settings',
            'static_settings' => 'Static Settings',
            'dynamic_settings' => 'Dynamic Settings',
            'message_settings' => 'Message Management',
            'visibility_settings' => 'Visibility Settings',
            'tag_settings' => 'Tag Settings',
            'font_settings' => 'Font Settings',
            'background_settings' => 'Background Settings',
            'size_settings' => 'Size Settings',
            'appearance_settings' => 'Appearance Settings'
        ],
        'ar' => [
            'page_title' => 'إعدادات مولد QR',
            'general_tab' => 'الإعدادات العامة',
            'static_tab' => 'الإعدادات الثابتة',
            'dynamic_tab' => 'الإعدادات الديناميكية',
            'messages_tab' => 'إدارة الرسائل',
            'save_settings' => 'حفظ الإعدادات',
            'settings_saved' => 'تم حفظ الإعدادات بنجاح!',
            'security_error' => 'خطأ أمني! يرجى المحاولة مرة أخرى.',
            'files_updated' => 'تم تحديث الملفات بنجاح!',
            'settings_reset' => 'تم إعادة تعيين جميع الإعدادات إلى قيمها الافتراضية بنجاح.',
            'force_update_files' => 'تحديث إجباري للملفات',
            'confirm_force_update' => 'هل أنت متأكد؟ هذا الإجراء سينسخ جميع الملفات مرة أخرى.',
            'general_settings' => 'الإعدادات العامة',
            'static_settings' => 'الإعدادات الثابتة',
            'dynamic_settings' => 'الإعدادات الديناميكية',
            'message_settings' => 'إدارة الرسائل',
            'visibility_settings' => 'إعدادات الرؤية',
            'tag_settings' => 'إعدادات العلامات',
            'font_settings' => 'إعدادات الخط',
            'background_settings' => 'إعدادات الخلفية',
            'size_settings' => 'إعدادات الحجم',
            'appearance_settings' => 'إعدادات المظهر'
        ]
    ];
    
    return $admin_texts[$lang][$key] ?? $admin_texts['en'][$key] ?? $key;
}

// Function to get appropriate font family based on language
function qrcdr_get_font_family($lang = null) {
    if ($lang === null) {
        $lang = qrcdr_get_wp_language();
    }
    
    $fonts = [
        'fa' => "'Vazir', 'Tahoma', 'Arial', sans-serif",
        'ar' => "'Cairo', 'Tahoma', 'Arial', sans-serif",
        'en' => "'Arial', 'Helvetica', sans-serif",
        'tr' => "'Arial', 'Helvetica', sans-serif",
        'ru' => "'Arial', 'Helvetica', sans-serif",
        'fr' => "'Arial', 'Helvetica', sans-serif",
        'de' => "'Arial', 'Helvetica', sans-serif"
    ];
    
    return $fonts[$lang] ?? $fonts['en'];
}

// Function to get form field text based on language
function qrcdr_get_form_text($key, $lang = null) {
    if ($lang === null) {
        $lang = qrcdr_get_wp_language();
    }
    
    $form_texts = [
        'fa' => [
            'default_language' => 'زبان پیش‌فرض:',
            'google_api_key' => 'کلید API گوگل:',
            'latitude' => 'عرض جغرافیایی:',
            'longitude' => 'طول جغرافیایی:',
            'show_title' => 'نمایش عنوان:',
            'show_text' => 'نمایش متن:',
            'show_tabs' => 'نمایش تب‌ها:',
            'show_navigation' => 'نمایش ناوبری:',
            'show_dynamic' => 'نمایش داینامیک:',
            'show_static' => 'نمایش استاتیک:',
            'title_tag' => 'تگ عنوان:',
            'text_tag' => 'تگ متن:',
            'tab_tag' => 'تگ تب:',
            'appearance_settings' => 'تنظیمات ظاهری'
        ],
        'en' => [
            'default_language' => 'Default Language:',
            'google_api_key' => 'Google API Key:',
            'latitude' => 'Latitude:',
            'longitude' => 'Longitude:',
            'show_title' => 'Show Title:',
            'show_text' => 'Show Text:',
            'show_tabs' => 'Show Tabs:',
            'show_navigation' => 'Show Navigation:',
            'show_dynamic' => 'Show Dynamic:',
            'show_static' => 'Show Static:',
            'title_tag' => 'Title Tag:',
            'text_tag' => 'Text Tag:',
            'tab_tag' => 'Tab Tag:',
            'appearance_settings' => 'Appearance Settings'
        ],
        'ar' => [
            'default_language' => 'اللغة الافتراضية:',
            'google_api_key' => 'مفتاح API جوجل:',
            'latitude' => 'خط العرض:',
            'longitude' => 'خط الطول:',
            'show_title' => 'إظهار العنوان:',
            'show_text' => 'إظهار النص:',
            'show_tabs' => 'إظهار التبويبات:',
            'show_navigation' => 'إظهار التنقل:',
            'show_dynamic' => 'إظهار الديناميكي:',
            'show_static' => 'إظهار الثابت:',
            'title_tag' => 'علامة العنوان:',
            'text_tag' => 'علامة النص:',
            'text_tag' => 'علامة التبويب:',
            'appearance_settings' => 'إعدادات المظهر'
        ]
    ];
    
    return $form_texts[$lang][$key] ?? $form_texts['en'][$key] ?? $key;
}

// CSS update function removed - no longer needed with Vue frontend


function QrCode_Settings(): void
{
    // Use global variables
    global $qrcdr_dynamic_buttons, $qrcdr_default_dynamic_texts, $qrcdr_static_buttons, $qrcdr_dynamic_button_texts, $qrcdr_static_button_texts;
    
    $dynamic_buttons = $qrcdr_dynamic_buttons;
    $default_dynamic_texts = $qrcdr_default_dynamic_texts;
    $static_buttons = $qrcdr_static_buttons;
    $dynamic_button_texts = $qrcdr_dynamic_button_texts;
    $static_button_texts = $qrcdr_static_button_texts;
    
    // Define default messages at the beginning of the function
    $default_messages = [
        'fa' => [
            'title' => 'کیو آر کد ساز آنلاین',
            'description' => 'ساخت کیو آر کد با قابلیت شخصی‌سازی',
            'topTitle' => 'کیو آر کد ساز آنلاین',
            'topText' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ',
            'staticTabText' => 'ساخت کیو آر کد استاتیک',
            'dynamicTabText' => 'ساخت کیو آر کد دینامیک',
            'buttons' => [],
            'buttons_text' => [],
            'dynamicButtons' => []
        ],
        'en' => [
            'title' => 'Online QR Code Generator',
            'description' => 'Create QR codes with customization options',
            'topTitle' => 'Online QR Code Generator',
            'topText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
            'staticTabText' => 'Create Static QR Code',
            'dynamicTabText' => 'Create Dynamic QR Code',
            'buttons' => [],
            'buttons_text' => [],
            'dynamicButtons' => []
        ],
        'ar' => [
            'title' => 'مولد رمز QR عبر الإنترنت',
            'description' => 'إنشاء رموز QR مع خيارات التخصيص',
            'topTitle' => 'مولد رمز QR عبر الإنترنت',
            'topText' => 'نص تجريبي لوريم إيبسوم',
            'staticTabText' => 'إنشاء رمز QR ثابت',
            'dynamicTabText' => 'إنشاء رمز QR ديناميكي',
            'buttons' => [],
            'buttons_text' => [],
            'dynamicButtons' => []
        ],
        'tr' => [
            'title' => 'Çevrimiçi QR Kod Üreticisi',
            'description' => 'Özelleştirme seçenekleri ile QR kod oluşturun',
            'topTitle' => 'Çevrimiçi QR Kod Üreticisi',
            'topText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
            'staticTabText' => 'Statik QR Kodu Oluştur',
            'dynamicTabText' => 'Dinamik QR Kodu Oluştur',
            'buttons' => [],
            'buttons_text' => [],
            'dynamicButtons' => []
        ],
        'ru' => [
            'title' => 'Онлайн генератор QR-кодов',
            'description' => 'Создавайте QR-коды с возможностью настройки',
            'topTitle' => 'Онлайн генератор QR-кодов',
            'topText' => 'Лорем ипсум долор сит амет, консектетур адиписицинг элит',
            'staticTabText' => 'Создать статический QR-код',
            'dynamicTabText' => 'Создать динамический QR-код',
            'buttons' => [],
            'buttons_text' => [],
            'dynamicButtons' => []
        ],
        'fr' => [
            'title' => 'Générateur de codes QR en ligne',
            'description' => 'Créez des codes QR avec des options de personnalisation',
            'topTitle' => 'Générateur de codes QR en ligne',
            'topText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
            'staticTabText' => 'Créer un code QR statique',
            'dynamicTabText' => 'Créer un code QR dynamique',
            'buttons' => [],
            'buttons_text' => [],
            'dynamicButtons' => []
        ],
        'de' => [
            'title' => 'Online QR-Code-Generator',
            'description' => 'Erstellen Sie QR-Codes mit Anpassungsoptionen',
            'topTitle' => 'Online QR-Code-Generator',
            'topText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
            'staticTabText' => 'Statischen QR-Code erstellen',
            'dynamicTabText' => 'Dynamischen QR-Code erstellen',
            'buttons' => [],
            'buttons_text' => [],
            'dynamicButtons' => []
        ]
    ];
    
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['qrcdr_nonce'] ?? '', 'qrcdr_settings_nonce')) {
            wp_die('Security check failed');
        }

        // Handle different tabs
        $active_tab = $_POST['active_tab'] ?? 'general';
    
            switch ($active_tab) {
            case 'general':
                // General settings
                update_option('qrcdr_google_api_key', sanitize_text_field($_POST['google_api_key'] ?? ''));
                update_option('qrcdr_lat', sanitize_text_field($_POST['lat'] ?? '40.7127837'));
                update_option('qrcdr_lng', sanitize_text_field($_POST['lng'] ?? '-74.00594130000002'));
                update_option('qrcdr_locale', sanitize_text_field($_POST['locale'] ?? 'fa'));
                update_option('qrcdr_default_language', sanitize_text_field($_POST['default_language'] ?? 'fa'));
                
                // Visibility settings
                update_option('qrcdr_visible_title', isset($_POST['visible_title']) ? '1' : '0');
                update_option('qrcdr_visible_text', isset($_POST['visible_text']) ? '1' : '0');
                update_option('qrcdr_visible_tabs', isset($_POST['visible_tabs']) ? '1' : '0');
                update_option('qrcdr_visible_nav_tab', isset($_POST['visible_nav_tab']) ? '1' : '0');
                update_option('qrcdr_visible_dynamic', isset($_POST['visible_dynamic']) ? '1' : '0');
                update_option('qrcdr_visible_static', isset($_POST['visible_static']) ? '1' : '0');
                
                // Tag settings
                update_option('qrcdr_selected_tag_title', sanitize_text_field($_POST['selected_tag_title'] ?? 'h2'));
                update_option('qrcdr_selected_tag_text', sanitize_text_field($_POST['selected_tag_text'] ?? 'h2'));
                update_option('qrcdr_selected_tag_tab', sanitize_text_field($_POST['selected_tag_tab'] ?? 'h2'));
                break;
                
            case 'static':
                // Static QR settings - Text content only
                // Visibility and tag settings moved to general tab
                
                // Text content - Multi-language
                update_option('qrcdr_static_fa_top_title', sanitize_text_field($_POST['static_fa_top_title'] ?? 'کیو آر کد ساز آنلاین'));
                update_option('qrcdr_static_fa_top_text', sanitize_textarea_field($_POST['static_fa_top_text'] ?? 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است'));
                update_option('qrcdr_static_fa_static_tab_text', sanitize_text_field($_POST['static_fa_static_tab_text'] ?? 'ساخت کیو آر کد استاتیک'));
                update_option('qrcdr_static_fa_dynamic_tab_text', sanitize_text_field($_POST['static_fa_dynamic_tab_text'] ?? 'ساخت کیو آر کد دینامیک'));
                update_option('qrcdr_static_fa_default_doc', sanitize_textarea_field($_POST['static_fa_default_doc'] ?? 'مستندات پیش‌فرض'));
                
                update_option('qrcdr_static_en_top_title', sanitize_text_field($_POST['static_en_top_title'] ?? 'Online QR Code Generator'));
                update_option('qrcdr_static_en_top_text', sanitize_textarea_field($_POST['static_en_top_text'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'));
                update_option('qrcdr_static_en_static_tab_text', sanitize_text_field($_POST['static_en_static_tab_text'] ?? 'Create Static QR Code'));
                update_option('qrcdr_static_en_dynamic_tab_text', sanitize_text_field($_POST['static_en_dynamic_tab_text'] ?? 'Create Dynamic QR Code'));
                update_option('qrcdr_static_en_default_doc', sanitize_textarea_field($_POST['static_en_default_doc'] ?? 'Default Documentation'));
                
                // Static button settings for all languages
                $languages = ['fa', 'en', 'ar', 'tr', 'ru', 'fr', 'de'];
                foreach ($languages as $lang) {
                    foreach ($static_buttons as $btn) {
                        // Button name
                        if (isset($_POST["static_{$lang}_button_{$btn}"])) {
                            update_option("qrcdr_static_{$lang}_button_{$btn}", sanitize_text_field($_POST["static_{$lang}_button_{$btn}"]));
                        }
                        
                        // Button icon
                        if (isset($_POST["static_{$lang}_icon_{$btn}"])) {
                            update_option("qrcdr_static_{$lang}_icon_{$btn}", sanitize_text_field($_POST["static_{$lang}_icon_{$btn}"]));
                        }
                        
                        // Button description
                        if (isset($_POST["static_{$lang}_description_{$btn}"])) {
                            update_option("qrcdr_static_{$lang}_description_{$btn}", sanitize_textarea_field($_POST["static_{$lang}_description_{$btn}"]));
                        }
                        
                        // Button title
                        if (isset($_POST["static_{$btn}_title_{$lang}"])) {
                            update_option("qrcdr_static_{$btn}_title_{$lang}", sanitize_text_field($_POST["static_{$btn}_title_{$lang}"]));
                        }
                    }
                }
                
                // Process static button specific settings (not language-specific)
                foreach ($static_buttons as $btn) {
                    // Visibility
                    if (isset($_POST["static_{$btn}_visible"])) {
                        update_option("qrcdr_static_{$btn}_visible", '1');
                    } else {
                        update_option("qrcdr_static_{$btn}_visible", '0');
                    }
                    
                    // Icon
                    if (isset($_POST["static_{$btn}_icon"])) {
                        update_option("qrcdr_static_{$btn}_icon", sanitize_text_field($_POST["static_{$btn}_icon"]));
                    }
                    
                    // Color
                    if (isset($_POST["static_{$btn}_color"])) {
                        update_option("qrcdr_static_{$btn}_color", sanitize_hex_color($_POST["static_{$btn}_color"]));
                    }
                    
                    // Multi-language titles and descriptions
                    foreach (['fa', 'en'] as $lang) {
                        if (isset($_POST["static_{$btn}_title_{$lang}"])) {
                            update_option("qrcdr_static_{$btn}_title_{$lang}", sanitize_text_field($_POST["static_{$btn}_title_{$lang}"]));
                        }
                        if (isset($_POST["static_{$btn}_description_{$lang}"])) {
                            update_option("qrcdr_static_{$btn}_description_{$lang}", sanitize_textarea_field($_POST["static_{$btn}_description_{$lang}"]));
                        }
                    }
                }
                break;

        case 'dynamic':
            // Dynamic QR settings - Multi-language
            foreach ($dynamic_buttons as $button) {
                update_option("qrcdr_dynamic_{$button}_visible", isset($_POST["dynamic_{$button}_visible"]) ? '1' : '0');
        
                // New fields added
               update_option("qrcdr_dynamic_{$button}_icon_image", sanitize_text_field($_POST["dynamic_{$button}_icon_image"] ?? ''));
               update_option("qrcdr_dynamic_{$button}_sample_image", esc_url_raw($_POST["dynamic_{$button}_sample_image"] ?? ''));
               update_option("qrcdr_dynamic_{$button}_button_color", sanitize_hex_color($_POST["dynamic_{$button}_button_color"] ?? '#E91E63'));
               update_option("qrcdr_dynamic_{$button}_link", esc_url_raw($_POST["dynamic_{$button}_link"] ?? ''));
               update_option("qrcdr_dynamic_{$button}_create_text", sanitize_text_field($_POST["dynamic_{$button}_create_text"] ?? 'ساخت کیوآر'));
               update_option("qrcdr_dynamic_{$button}_visit_text", sanitize_text_field($_POST["dynamic_{$button}_visit_text"] ?? 'بازدید از سایت'));
                
                // Persian text
                update_option("qrcdr_dynamic_{$button}_title_fa", sanitize_text_field($_POST["dynamic_{$button}_title_fa"] ?? ''));
                update_option("qrcdr_dynamic_{$button}_description_fa", sanitize_textarea_field($_POST["dynamic_{$button}_description_fa"] ?? ''));
        update_option("qrcdr_dynamic_{$button}_subtext_fa", sanitize_text_field($_POST["dynamic_{$button}_subtext_fa"] ?? ''));
                
                // English text
                update_option("qrcdr_dynamic_{$button}_title_en", sanitize_text_field($_POST["dynamic_{$button}_title_en"] ?? ''));
                update_option("qrcdr_dynamic_{$button}_description_en", sanitize_textarea_field($_POST["dynamic_{$button}_description_en"] ?? ''));
        update_option("qrcdr_dynamic_{$button}_subtext_en", sanitize_text_field($_POST["dynamic_{$button}_subtext_en"] ?? ''));
                
                // Arabic text
                update_option("qrcdr_dynamic_{$button}_title_ar", sanitize_text_field($_POST["dynamic_{$button}_title_ar"] ?? ''));
                update_option("qrcdr_dynamic_{$button}_description_ar", sanitize_textarea_field($_POST["dynamic_{$button}_description_ar"] ?? ''));
        update_option("qrcdr_dynamic_{$button}_subtext_ar", sanitize_text_field($_POST["dynamic_{$button}_subtext_ar"] ?? ''));
                
                // Turkish text
                update_option("qrcdr_dynamic_{$button}_title_tr", sanitize_text_field($_POST["dynamic_{$button}_title_tr"] ?? ''));
                update_option("qrcdr_dynamic_{$button}_description_tr", sanitize_textarea_field($_POST["dynamic_{$button}_description_tr"] ?? ''));
        update_option("qrcdr_dynamic_{$button}_subtext_tr", sanitize_text_field($_POST["dynamic_{$button}_subtext_tr"] ?? ''));
                
                // Russian text
                update_option("qrcdr_dynamic_{$button}_title_ru", sanitize_text_field($_POST["dynamic_{$button}_title_ru"] ?? ''));
                update_option("qrcdr_dynamic_{$button}_description_ru", sanitize_textarea_field($_POST["dynamic_{$button}_description_ru"] ?? ''));
        update_option("qrcdr_dynamic_{$button}_subtext_ru", sanitize_text_field($_POST["dynamic_{$button}_subtext_ru"] ?? ''));
                
                // French text
                update_option("qrcdr_dynamic_{$button}_title_fr", sanitize_text_field($_POST["dynamic_{$button}_title_fr"] ?? ''));
                update_option("qrcdr_dynamic_{$button}_description_fr", sanitize_textarea_field($_POST["dynamic_{$button}_description_fr"] ?? ''));
        update_option("qrcdr_dynamic_{$button}_subtext_fr", sanitize_text_field($_POST["dynamic_{$button}_subtext_fr"] ?? ''));
                
                // German text
                update_option("qrcdr_dynamic_{$button}_title_de", sanitize_text_field($_POST["dynamic_{$button}_title_de"] ?? ''));
                update_option("qrcdr_dynamic_{$button}_description_de", sanitize_textarea_field($_POST["dynamic_{$button}_description_de"] ?? ''));
        update_option("qrcdr_dynamic_{$button}_subtext_de", sanitize_text_field($_POST["dynamic_{$button}_subtext_de"] ?? ''));
            }
            break;

                
            case 'messages':
                // Messages management for different languages
                $languages = ['fa', 'en', 'ar', 'tr', 'ru', 'fr', 'de'];
                $message_keys = [
                    'title', 'description', 'topTitle', 'topText', 'staticTabText', 'dynamicTabText',
                    'buttons', 'buttons_text', 'dynamicButtons', 'dynamicButtons_subtext', 
                    'dynamicButtons_text2', 'dynamicButtons_doc', 'generate_qrcode', 
                    'error_correction_level', 'frame_selection', 'color_selection', 
                    'visit_website', 'more_info'
                ];
                
                foreach ($languages as $lang) {
                    foreach ($message_keys as $key) {
                        if (isset($_POST["messages_{$lang}_{$key}"])) {
                            if (is_array($_POST["messages_{$lang}_{$key}"])) {
                                // Sanitize array values
                                $sanitized_array = [];
                                foreach ($_POST["messages_{$lang}_{$key}"] as $array_key => $array_value) {
                                    if ($key === 'dynamicButtons_doc') {
                                        $sanitized_array[sanitize_key($array_key)] = sanitize_textarea_field($array_value);
                                    } else {
                                        $sanitized_array[sanitize_key($array_key)] = sanitize_text_field($array_value);
                                    }
                                }
                                update_option("qrcdr_messages_{$lang}_{$key}", $sanitized_array);
                            } else {
                                if (in_array($key, ['topText', 'dynamicButtons_doc'])) {
                                    update_option("qrcdr_messages_{$lang}_{$key}", sanitize_textarea_field($_POST["messages_{$lang}_{$key}"]));
                                } else {
                                    update_option("qrcdr_messages_{$lang}_{$key}", sanitize_text_field($_POST["messages_{$lang}_{$key}"]));
                                }
                            }
                        }
                    }
                }
                break;
                
            case 'appearance':
                // Appearance settings
                update_option('qrcdr_orgin_bg_color', sanitize_hex_color($_POST['orgin_bg_color'] ?? '#ffffff'));
                update_option('qrcdr_menu_bg_color', sanitize_hex_color($_POST['menu_bg_color'] ?? '#403199'));
                update_option('qrcdr_active_menu_color', sanitize_hex_color($_POST['active_menu_color'] ?? '#ab9fee'));
                update_option('qrcdr_icon_color', sanitize_hex_color($_POST['icon_color'] ?? '#ffffff'));
                update_option('qrcdr_top_bg_color', sanitize_hex_color($_POST['top_bg_color'] ?? '#fbe0e3'));
                update_option('qrcdr_tab_bg_color', sanitize_hex_color($_POST['tab_bg_color'] ?? '#ffffff'));
                update_option('qrcdr_active_tab_color', sanitize_hex_color($_POST['active_tab_color'] ?? '#403199'));
                update_option('qrcdr_button_color', sanitize_hex_color($_POST['button_color'] ?? '#e9354d'));
                update_option('qrcdr_icon_bg_color', sanitize_hex_color($_POST['icon_bg_color'] ?? '#ff4d4d'));
                update_option('qrcdr_bottom_line_color', sanitize_hex_color($_POST['bottom_line_color'] ?? '#403199'));
                
                // Font settings
                update_option('qrcdr_main_font_family', sanitize_text_field($_POST['main_font_family'] ?? 'Vazir'));
                update_option('qrcdr_custom_font_url', esc_url_raw($_POST['custom_font_url'] ?? ''));
                
                // Background options
                update_option('qrcdr_background_type', sanitize_text_field($_POST['background_type'] ?? 'color'));
                update_option('qrcdr_background_image', sanitize_text_field($_POST['background_image'] ?? ''));
                update_option('qrcdr_background_transparency', sanitize_text_field($_POST['background_transparency'] ?? '0'));
                
                // Gradient settings
                update_option('qrcdr_gradient_color_1', sanitize_hex_color($_POST['gradient_color_1'] ?? '#ffffff'));
                update_option('qrcdr_gradient_color_2', sanitize_hex_color($_POST['gradient_color_2'] ?? '#403199'));
                update_option('qrcdr_gradient_direction', sanitize_text_field($_POST['gradient_direction'] ?? '135deg'));
                
                // Size settings
                update_option('qrcdr_tab_size', sanitize_text_field($_POST['tab_size'] ?? '50%'));
                update_option('qrcdr_text_size', sanitize_text_field($_POST['text_size'] ?? '12px'));
                update_option('qrcdr_tab_text_size', sanitize_text_field($_POST['tab_text_size'] ?? '16px'));
                update_option('qrcdr_tab_text_active_size', sanitize_text_field($_POST['tab_text_active_size'] ?? '17px'));
                update_option('qrcdr_button_size', sanitize_text_field($_POST['button_size'] ?? '100px'));
                update_option('qrcdr_icon_size', sanitize_text_field($_POST['icon_size'] ?? '12px'));
                break;
        }
        
        // Settings updated successfully
        $current_lang = qrcdr_get_wp_language();
        $admin_text = [];
        foreach (['settings_saved'] as $key) {
            $admin_text[$key] = qrcdr_get_admin_text($key, $current_lang);
        }
        if (empty($admin_text['settings_saved'])) {
            $admin_text['settings_saved'] = $current_lang === 'fa' ? 'تنظیمات با موفقیت ذخیره شد!' : 'Settings saved successfully!';
        }
        
        echo '<div class="notice notice-success"><p>' . esc_html($admin_text['settings_saved']) . '</p></div>';
    }
    
    // Get current tab
    $active_tab = $_GET['tab'] ?? 'general';
    
    // Get WordPress locale and determine language and direction
    $current_lang = qrcdr_get_wp_language();
    $is_rtl = qrcdr_is_rtl();
    
    // Get saved values
    $google_api_key = get_option('qrcdr_google_api_key', '');
    $lat = get_option('qrcdr_lat', '40.7127837');
    $lng = get_option('qrcdr_lng', '-74.00594130000002');
    $locale = get_option('qrcdr_locale', $current_lang);
    $default_language = get_option('qrcdr_default_language', $current_lang);
    
    $visible_title = get_option('qrcdr_visible_title', '1');
    $visible_text = get_option('qrcdr_visible_text', '1');
    $visible_tabs = get_option('qrcdr_visible_tabs', '1');
    $visible_nav_tab = get_option('qrcdr_visible_nav_tab', '1');
    $visible_dynamic = get_option('qrcdr_visible_dynamic', '0');
    $visible_static = get_option('qrcdr_visible_static', '0');
    
    $selected_tag_title = get_option('qrcdr_selected_tag_title', 'h2');
    $selected_tag_text = get_option('qrcdr_selected_tag_text', 'h2');
    $selected_tag_tab = get_option('qrcdr_selected_tag_tab', 'h2');
    
    // Static text settings - Multi-language
    $static_fa_top_title = get_option('qrcdr_static_fa_top_title', 'کیو آر کد ساز آنلاین');
    $static_fa_top_text = get_option('qrcdr_static_fa_top_text', 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است');
    $static_fa_static_tab_text = get_option('qrcdr_static_fa_static_tab_text', 'ساخت کیو آر کد استاتیک');
    $static_fa_dynamic_tab_text = get_option('qrcdr_static_fa_dynamic_tab_text', 'ساخت کیو آر کد دینامیک');
    $static_fa_default_doc = get_option('qrcdr_static_fa_default_doc', 'مستندات پیش‌فرض');
    
    $static_en_top_title = get_option('qrcdr_static_en_top_title', 'Online QR Code Generator');
    $static_en_top_text = get_option('qrcdr_static_en_top_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
    $static_en_static_tab_text = get_option('qrcdr_static_en_static_tab_text', 'Create Static QR Code');
    $static_en_dynamic_tab_text = get_option('qrcdr_static_en_dynamic_tab_text', 'Create Dynamic QR Code');
    $static_en_default_doc = get_option('qrcdr_static_en_default_doc', 'Default Documentation');
    
    $orgin_bg_color = get_option('qrcdr_orgin_bg_color', '#ffffff');
    $menu_bg_color = get_option('qrcdr_menu_bg_color', '#403199');
    $active_menu_color = get_option('qrcdr_active_menu_color', '#ab9fee');
    $icon_color = get_option('qrcdr_icon_color', '#ffffff');
    $top_bg_color = get_option('qrcdr_top_bg_color', '#fbe0e3');
    $tab_bg_color = get_option('qrcdr_tab_bg_color', '#ffffff');
    $active_tab_color = get_option('qrcdr_active_tab_color', '#403199');
    $button_color = get_option('qrcdr_button_color', '#e9354d');
    $icon_bg_color = get_option('qrcdr_icon_bg_color', '#ff4d4d');
    $bottom_line_color = get_option('qrcdr_bottom_line_color', '#403199');
    
    $tab_size = get_option('qrcdr_tab_size', '50%');
    $text_size = get_option('qrcdr_text_size', '12px');
    $tab_text_size = get_option('qrcdr_tab_text_size', '16px');
    $tab_text_active_size = get_option('qrcdr_tab_text_active_size', '17px');
    $button_size = get_option('qrcdr_button_size', '100px');
    $icon_size = get_option('qrcdr_icon_size', '12px');
    
    // Font settings
    $main_font_family = get_option('qrcdr_main_font_family', 'Vazir');
    $custom_font_url = get_option('qrcdr_custom_font_url', '');
    
    // Background settings
    $background_type = get_option('qrcdr_background_type', 'color');
    $background_image = get_option('qrcdr_background_image', '');
    $background_transparency = get_option('qrcdr_background_transparency', '0');
    
    // Gradient settings
    $gradient_color_1 = get_option('qrcdr_gradient_color_1', '#ffffff');
    $gradient_color_2 = get_option('qrcdr_gradient_color_2', '#403199');
    $gradient_direction = get_option('qrcdr_gradient_direction', '135deg');
    
    // Dynamic buttons
    $dynamic_buttons = ['business_card', 'social_media', 'photo_gallery', 'video_gallery', 'link', 'website', 'online_menu', 'insurance', 'online_shop', 'booking', 'commercial_complex', 'product', 'product_label', 'gold_label', 'catalog'];
    
    // Get admin texts based on current language
    $admin_text = [];
    foreach (['page_title', 'general_tab', 'static_tab', 'dynamic_tab', 'messages_tab', 'appearance_settings', 'save_settings', 'settings_saved', 'security_error', 'files_updated', 'settings_reset', 'force_update_files', 'reset_all_tabs', 'confirm_force_update', 'confirm_reset', 'general_settings', 'static_settings', 'dynamic_settings', 'message_settings', 'visibility_settings', 'tag_settings', 'font_settings', 'background_settings', 'size_settings'] as $key) {
        $admin_text[$key] = qrcdr_get_admin_text($key, $current_lang);
    }
    
    // Messages from messages.js
$default_dynamic_texts = [
    'fa' => [
        'business_card' => ['title' => 'کارت ویزیت', 'description' => 'ایجاد QR کد برای کارت ویزیت'],
        'social_media' => ['title' => 'شبکه‌های اجتماعی', 'description' => 'ایجاد QR کد برای شبکه‌های اجتماعی'],
        'photo_gallery' => ['title' => 'گالری عکس', 'description' => 'ایجاد QR کد برای گالری عکس'],
        'video_gallery' => ['title' => 'گالری ویدیو', 'description' => 'ایجاد QR کد برای گالری ویدیو'],
        'link' => ['title' => 'لینک', 'description' => 'ایجاد QR کد برای لینک'],
        'website' => ['title' => 'وب‌سایت', 'description' => 'ایجاد QR کد برای وب‌سایت'],
        'online_menu' => ['title' => 'منوی آنلاین', 'description' => 'ایجاد QR کد برای منوی آنلاین'],
        'insurance' => ['title' => 'بیمه', 'description' => 'ایجاد QR کد برای بیمه'],
        'online_shop' => ['title' => 'فروشگاه آنلاین', 'description' => 'ایجاد QR کد برای فروشگاه آنلاین'],
        'booking' => ['title' => 'رزرو', 'description' => 'ایجاد QR کد برای رزرو'],
        'commercial_complex' => ['title' => 'مجتمع تجاری', 'description' => 'ایجاد QR کد برای مجتمع تجاری'],
        'product' => ['title' => 'محصول', 'description' => 'ایجاد QR کد برای محصول'],
        'product_label' => ['title' => 'برچسب محصول', 'description' => 'ایجاد QR کد برای برچسب محصول'],
        'gold_label' => ['title' => 'برچسب طلا', 'description' => 'ایجاد QR کد برای برچسب طلا'],
        'catalog' => ['title' => 'کاتالوگ', 'description' => 'ایجاد QR کد برای کاتالوگ']
    ],
    'en' => [
        'business_card' => ['title' => 'Business Card', 'description' => 'Create QR code for business card'],
        'social_media' => ['title' => 'Social Media', 'description' => 'Create QR code for social media'],
        'photo_gallery' => ['title' => 'Photo Gallery', 'description' => 'Create QR code for photo gallery'],
        'video_gallery' => ['title' => 'Video Gallery', 'description' => 'Create QR code for video gallery'],
        'link' => ['title' => 'Link', 'description' => 'Create QR code for link'],
        'website' => ['title' => 'Website', 'description' => 'Create QR code for website'],
        'online_menu' => ['title' => 'Online Menu', 'description' => 'Create QR code for online menu'],
        'insurance' => ['title' => 'Insurance', 'description' => 'Create QR code for insurance'],
        'online_shop' => ['title' => 'Online Shop', 'description' => 'Create QR code for online shop'],
        'booking' => ['title' => 'Booking', 'description' => 'Create QR code for booking'],
        'commercial_complex' => ['title' => 'Commercial Complex', 'description' => 'Create QR code for commercial complex'],
        'product' => ['title' => 'Product', 'description' => 'Create QR code for product'],
        'product_label' => ['title' => 'Product Label', 'description' => 'Create QR code for product label'],
        'gold_label' => ['title' => 'Gold Label', 'description' => 'Create QR code for gold label'],
        'catalog' => ['title' => 'Catalog', 'description' => 'Create QR code for catalog']
    ],
    'ar' => [
        'business_card' => ['title' => 'بطاقة عمل', 'description' => 'إنشاء رمز QR لبطاقة العمل'],
        'social_media' => ['title' => 'وسائل التواصل الاجتماعي', 'description' => 'إنشاء رمز QR لوسائل التواصل الاجتماعي'],
        'photo_gallery' => ['title' => 'معرض الصور', 'description' => 'إنشاء رمز QR لمعرض الصور'],
        'video_gallery' => ['title' => 'معرض الفيديو', 'description' => 'إنشاء رمز QR لمعرض الفيديو'],
        'link' => ['title' => 'رابط', 'description' => 'إنشاء رمز QR للرابط'],
        'website' => ['title' => 'موقع ويب', 'description' => 'إنشاء رمز QR للموقع'],
        'online_menu' => ['title' => 'قائمة طعام على الإنترنت', 'description' => 'إنشاء رمز QR للقائمة'],
        'insurance' => ['title' => 'تأمين', 'description' => 'إنشاء رمز QR للتأمين'],
        'online_shop' => ['title' => 'متجر على الإنترنت', 'description' => 'إنشاء رمز QR للمتجر'],
        'booking' => ['title' => 'حجز', 'description' => 'إنشاء رمز QR للحجز'],
        'commercial_complex' => ['title' => 'مجمع تجاري', 'description' => 'إنشاء رمز QR للمجمع'],
        'product' => ['title' => 'منتج', 'description' => 'إنشاء رمز QR للمنتج'],
        'product_label' => ['title' => 'ملصق المنتج', 'description' => 'إنشاء رمز QR للملصق'],
        'gold_label' => ['title' => 'ملصق ذهبي', 'description' => 'إنشاء رمز QR للملصق الذهبي'],
        'catalog' => ['title' => 'كتالوج', 'description' => 'إنشاء رمز QR للكتالوج']
    ],
    'tr' => [
        'business_card' => ['title' => 'İş Kartı', 'description' => 'İş kartı için QR kod oluştur'],
        'social_media' => ['title' => 'Sosyal Medya', 'description' => 'Sosyal medya için QR kod oluştur'],
        'photo_gallery' => ['title' => 'Fotoğraf Galerisi', 'description' => 'Fotoğraf galerisi için QR kod oluştur'],
        'video_gallery' => ['title' => 'Video Galerisi', 'description' => 'Video galerisi için QR kod oluştur'],
        'link' => ['title' => 'Bağlantı', 'description' => 'Bağlantı için QR kod oluştur'],
        'website' => ['title' => 'Web Sitesi', 'description' => 'Web sitesi için QR kod oluştur'],
        'online_menu' => ['title' => 'Çevrimiçi Menü', 'description' => 'Çevrimiçi menü için QR kod oluştur'],
        'insurance' => ['title' => 'Sigorta', 'description' => 'Sigorta için QR kod oluştur'],
        'online_shop' => ['title' => 'Çevrimiçi Mağaza', 'description' => 'Çevrimiçi mağaza için QR kod oluştur'],
        'booking' => ['title' => 'Rezervasyon', 'description' => 'Rezervasyon için QR kod oluştur'],
        'commercial_complex' => ['title' => 'Ticari Kompleks', 'description' => 'Ticari kompleks için QR kod oluştur'],
        'product' => ['title' => 'Ürün', 'description' => 'Ürün için QR kod oluştur'],
        'product_label' => ['title' => 'Ürün Etiketi', 'description' => 'Ürün etiketi için QR kod oluştur'],
        'gold_label' => ['title' => 'Altın Etiket', 'description' => 'Altın etiket için QR kod oluştur'],
        'catalog' => ['title' => 'Katalog', 'description' => 'Katalog için QR kod oluştur']
    ],
    'ru' => [
        'business_card' => ['title' => 'Визитная карточка', 'description' => 'Создать QR-код для визитной карточки'],
        'social_media' => ['title' => 'Социальные сети', 'description' => 'Создать QR-код для социальных сетей'],
        'photo_gallery' => ['title' => 'Фотогалерея', 'description' => 'Создать QR-код для фотогалереи'],
        'video_gallery' => ['title' => 'Видеогалерея', 'description' => 'Создать QR-کود для видеогалереи'],
        'link' => ['title' => 'Ссылка', 'description' => 'Создать QR-код для ссылки'],
        'website' => ['title' => 'Веб-сайт', 'description' => 'Создать QR-код для веб-сайта'],
        'online_menu' => ['title' => 'Онлайн меню', 'description' => 'Создать QR-код для онлайн меню'],
        'insurance' => ['title' => 'Страхование', 'description' => 'Создать QR-код для страхования'],
        'online_shop' => ['title' => 'Онлайн магазин', 'description' => 'Создать QR-код для онлайн магазина'],
        'booking' => ['title' => 'Бронирование', 'description' => 'Создать QR-код для бронирования'],
        'commercial_complex' => ['title' => 'Торговый комплекс', 'description' => 'Создать QR-код для торгового комплекса'],
        'product' => ['title' => 'Продукт', 'description' => 'Создать QR-код для продукта'],
        'product_label' => ['title' => 'Этикетка продукта', 'description' => 'Создать QR-код для этикетки продукта'],
        'gold_label' => ['title' => 'Золотая этикетка', 'description' => 'Создать QR-код для золотой этикетки'],
        'catalog' => ['title' => 'Каталог', 'description' => 'Создать QR-код для каталога']
    ],
    'fr' => [
        'business_card' => ['title' => 'Carte de visite', 'description' => 'Créer un code QR pour la carte de visite'],
        'social_media' => ['title' => 'Réseaux sociaux', 'description' => 'Créer un code QR pour les réseaux sociaux'],
        'photo_gallery' => ['title' => 'Galerie photo', 'description' => 'Créer un code QR pour la galerie photo'],
        'video_gallery' => ['title' => 'Galerie vidéo', 'description' => 'Créer un code QR pour la galerie vidéo'],
        'link' => ['title' => 'Lien', 'description' => 'Créer un code QR pour le lien'],
        'website' => ['title' => 'Site web', 'description' => 'Créer un code QR pour le site web'],
        'online_menu' => ['title' => 'Menu en ligne', 'description' => 'Créer un code QR pour le menu en ligne'],
        'insurance' => ['title' => 'Assurance', 'description' => 'Créer un code QR pour l\'assurance'],
        'online_shop' => ['title' => 'Boutique en ligne', 'description' => 'Créer un code QR pour la boutique en ligne'],
        'booking' => ['title' => 'Réservation', 'description' => 'Créer un code QR pour la réservation'],
        'commercial_complex' => ['title' => 'Complexe commercial', 'description' => 'Créer un code QR pour le complexe commercial'],
        'product' => ['title' => 'Produit', 'description' => 'Créer un code QR pour le produit'],
        'product_label' => ['title' => 'Étiquette produit', 'description' => 'Créer un code QR pour l\'étiquette produit'],
        'gold_label' => ['title' => 'Étiquette dorée', 'description' => 'Créer un code QR pour l\'étiquette dorée'],
        'catalog' => ['title' => 'Catalogue', 'description' => 'Créer un code QR pour le catalogue']
    ],
    'de' => [
        'business_card' => ['title' => 'Visitenkarte', 'description' => 'QR-Code für Visitenkarte erstellen'],
        'social_media' => ['title' => 'Soziale Medien', 'description' => 'QR-Code für soziale Medien erstellen'],
        'photo_gallery' => ['title' => 'Fotogalerie', 'description' => 'QR-Code für Fotogalerie erstellen'],
        'video_gallery' => ['title' => 'Videogalerie', 'description' => 'QR-Code für Videogalerie erstellen'],
        'link' => ['title' => 'Link', 'description' => 'QR-Code für Link erstellen'],
        'website' => ['title' => 'Website', 'description' => 'QR-Code für Website erstellen'],
        'online_menu' => ['title' => 'Online-Menü', 'description' => 'QR-Code für Online-Menü erstellen'],
        'insurance' => ['title' => 'Versicherung', 'description' => 'QR-Code für Versicherung erstellen'],
        'online_shop' => ['title' => 'Online-Shop', 'description' => 'QR-Code für Online-Shop erstellen'],
        'booking' => ['title' => 'Buchung', 'description' => 'QR-Code für Buchung erstellen'],
        'commercial_complex' => ['title' => 'Gewerbekomplex', 'description' => 'QR-Code für Gewerbekomplex erstellen'],
        'product' => ['title' => 'Produkt', 'description' => 'QR-Code für Produkt erstellen'],
        'product_label' => ['title' => 'Produktetikett', 'description' => 'QR-Code für Produktetikett erstellen'],
        'gold_label' => ['title' => 'Gold-Etikett', 'description' => 'QR-Code für Gold-Etikett erstellen'],
        'catalog' => ['title' => 'Katalog', 'description' => 'QR-Code für Katalog erstellen']
    ]
];
foreach ($dynamic_buttons as $button) {
                       // Set default values for dynamic button fields
           if (!get_option("qrcdr_dynamic_{$button}_icon_image")) {
               update_option("qrcdr_dynamic_{$button}_icon_image", '');
           }
           if (!get_option("qrcdr_dynamic_{$button}_sample_image")) {
               update_option("qrcdr_dynamic_{$button}_sample_image", '');
           }
    if (!get_option("qrcdr_dynamic_{$button}_button_color")) {
        update_option("qrcdr_dynamic_{$button}_button_color", '#E91E63');
    }
    if (!get_option("qrcdr_dynamic_{$button}_link")) {
        update_option("qrcdr_dynamic_{$button}_link", '');
    }
    if (!get_option("qrcdr_dynamic_{$button}_create_text")) {
        update_option("qrcdr_dynamic_{$button}_create_text", 'ساخت کیوآر');
    }
    if (!get_option("qrcdr_dynamic_{$button}_visit_text")) {
        update_option("qrcdr_dynamic_{$button}_visit_text", 'بازدید از سایت');
    }
    $languages = ['fa', 'en', 'ar', 'tr', 'ru', 'fr', 'de'];
    foreach ($languages as $lang) {
        if (!get_option("qrcdr_dynamic_{$button}_subtext_{$lang}")) {
            update_option("qrcdr_dynamic_{$button}_subtext_{$lang}", '');
        }
    }
}
    
    // Handle force update
    if (isset($_POST['force_update_files'])) {
        if (wp_verify_nonce($_POST['qrcdr_force_update_nonce'] ?? '', 'qrcdr_force_update_nonce')) {
        qrcdr_force_update_files();
        echo '<div class="notice notice-success"><p>' . esc_html($admin_text['files_updated']) . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . esc_html($admin_text['security_error']) . '</p></div>';
    }
    }
    



// Handle reset success message
if (isset($_GET['reset_success']) && $_GET['reset_success'] === '1') {
    $current_lang = qrcdr_get_wp_language();
    $admin_text = [];
    $admin_text['settings_reset'] = qrcdr_get_admin_text('settings_reset', $current_lang);
    
    echo '<div class="notice notice-success"><p>' . esc_html($admin_text['settings_reset']) . '</p></div>';
}
?>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style scoped>        
        :root {
            --rainbow-gradient: linear-gradient(135deg, #ff6b6b, #feca57, #48dbfb, #ff9ff3, #54a0ff, #5f27cd, #00d2d3);
            --rainbow-gradient-soft: linear-gradient(135deg, rgba(255,107,107,0.1), rgba(254,202,87,0.1), rgba(72,219,251,0.1), rgba(255,159,243,0.1), rgba(84,160,255,0.1), rgba(95,39,205,0.1), rgba(0,210,211,0.1));
            --rainbow-gradient-hover: linear-gradient(135deg, rgba(255,107,107,0.2), rgba(254,202,87,0.2), rgba(72,219,251,0.2), rgba(255,159,243,0.2), rgba(84,160,255,0.2), rgba(95,39,205,0.2), rgba(0,210,211,0.2));
            --primary-bg: #ffffff;
            --secondary-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-large: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .wrap {
            background: var(--primary-bg);
            border: none;
            border-radius: var(--border-radius);
            padding: 30px;
            max-width: 1200px;
            margin: 30px auto;
            font-family: 'Vazir', 'Tahoma', sans-serif;
            box-shadow: var(--shadow-large);
            position: relative;
            overflow: hidden;
        }
        
        /* RTL Support */
        .wrap[dir="rtl"] {
            font-family: <?php echo qrcdr_get_font_family('fa'); ?>;
            text-align: right;
        }
        
        .wrap[dir="rtl"] .form-row label {
            text-align: right;
            margin-left: 0;
            margin-right: 10px;
            width:230px;

        }
        
        .wrap[dir="rtl"] .nav-tab {
            margin-right: 0;
            margin-left: 8px;
        }
        
        .wrap[dir="ltr"] {
            font-family: <?php echo qrcdr_get_font_family('en'); ?>;
            text-align: left;
        }
        
        .wrap[dir="ltr"] .form-row label {
            text-align: left;
            margin-right: 0;
            margin-left: 10px;
            width:230px;
        }
        
        .wrap[dir="ltr"] .nav-tab {
            margin-left: 0;
            margin-right: 8px;
        }
        
        /* Modern Form Layout - 6 columns grid */
        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
            flex-wrap: wrap;
            width: 100%;
            flex-direction: row;
        }
        
        .form-row label {
            min-width: 150px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            margin-bottom: 5px;
            width:230px;
            text-align: <?php echo in_array($current_lang, ['fa', 'ar']) ? 'right' : 'left'; ?>;

        }
        
        .form-row input[type="text"],
        .form-row input[type="url"],
        .form-row input[type="email"],
        .form-row input[type="number"],
        .form-row select,
        .form-row textarea {
            flex: 1;
            min-width: 200px;
            max-width: 300px;
            border: 2px solid;
            /* border-image: var(--rainbow-gradient) 1; */
            border-color: var(--rainbow-gradient) !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .form-row input[type="text"]:hover,
        .form-row input[type="url"]:hover,
        .form-row input[type="email"]:hover,
        .form-row input[type="number"]:hover,
        .form-row select:hover,
        .form-row textarea:hover {
            border-width: 3px;
            box-shadow: 0 4px 15px var(--rainbow-gradient-soft);
            transform: translateY(-2px);
        }
        
        .form-row input[type="text"]:focus,
        .form-row input[type="url"]:focus,
        .form-row input[type="email"]:focus,
        .form-row input[type="number"]:focus,
        .form-row select:focus,
        .form-row textarea:focus {
            outline: none;
            border-width: 3px;
            box-shadow: 0 0 0 4px var(--rainbow-gradient-soft), 0 6px 20px var(--rainbow-gradient-soft);
            transform: translateY(-2px);
        }
        
        /* Small input fields for numbers and short text */
        .form-row input[type="number"],
        .form-row input[type="text"].small-input {
            max-width: 120px;
            min-width: 100px;
            text-align: center;
        }
        
        /* Two inputs per row layout */
        .form-row.two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: start;
        }
        
        .form-row.two-columns .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-row.two-columns label {
            min-width: auto;
            margin-bottom: 5px;
            text-align: <?php echo in_array($current_lang, ['fa', 'ar']) ? 'right' : 'left'; ?>;

        }
        
        /* Enhanced Button Styling with rainbow shadows instead of border-image */
       .wrap .button,
       .wrap .button-primary,
       .wrap .button-secondary,
       .wrap input[type="submit"] {
            border-radius: 20px !important;
            border: 2px solid #ffffff !important;
            background: #ffffff !important;
            color: #333 !important;
            font-weight: 700 !important;
            padding: 15px 30px !important;
            font-size: 14px !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
            position: relative !important;
            overflow: hidden !important;
        }
        
        /* Special Button Colors - Save (Blue), Reset (Green), Delete (Red) */
        .wrap .button[type="submit"],
        .wrap input[type="submit"] {
            background: rgba(52, 152, 219, 0.1) !important;
            border: 3px solid #3498db !important;
            color: #2980b9 !important;
        }
        
        .wrap .button[type="submit"]:hover,
        .wrap input[type="submit"]:hover {
            background: rgba(52, 152, 219, 0.2) !important;
            border-color: #2980b9 !important;
            box-shadow: 0 12px 30px rgba(52, 152, 219, 0.4) !important;
        }
        
        /* Reset Button - Green */
        .wrap .button[name="reset_all_tabs"],
        .wrap .button.reset,
        .wrap .reset-button {
            background: rgba(46, 204, 113, 0.1) !important;
            border: 3px solid #2ecc71 !important;
            color: #27ae60 !important;
        }
        
        .wrap .button[name="reset_all_tabs"]:hover,
        .wrap .button.reset:hover,
        .wrap .reset-button:hover {
            background: rgba(46, 204, 113, 0.2) !important;
            border-color: #27ae60 !important;
            box-shadow: 0 12px 30px rgba(46, 204, 113, 0.4) !important;
        }
        
        /* Delete Button - Red */
        .wrap .button.delete,
        .wrap .button-danger,
        .wrap button[onclick*="delete"] {
            background: rgba(231, 76, 60, 0.1) !important;
            border: 3px solid #e74c3c !important;
            color: #c0392b !important;
        }
        
        .wrap .button.delete:hover,
        .wrap .button-danger:hover,
        .wrap button[onclick*="delete"]:hover {
            background: rgba(231, 76, 60, 0.2) !important;
            border-color: #c0392b !important;
            box-shadow: 0 12px 30px rgba(231, 76, 60, 0.4) !important;
        }
        
        .wrap .button:hover,
        .wrap .button-primary:hover,
        .wrap .button-primary:hover,
        .wrap .button-secondary:hover,
        .wrap input[type="submit"]:hover {
            transform: translateY(-4px) scale(1.02) !important;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 3px var(--rainbow-gradient),
                0 12px 30px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border-width: 2px !important;
        }
        
        .wrap .button:active,
        .wrap .button-primary:active,
        .wrap .button-secondary:active,
        .wrap input[type="submit"]:active {
            transform: translateY(-2px) scale(0.98) !important;
        }
        
        /* Enhanced Active/Selected state with rainbow shadows */
        .wrap .button.active,
        .wrap .button-primary.active,
        .wrap .button-secondary.active,
        .wrap input[type="submit"].active,
        .wrap .nav-tab.active {
            background: var(--rainbow-gradient-soft) !important;
            border: 2px solid #ffffff !important;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 3px var(--rainbow-gradient),
                0 0 30px var(--rainbow-gradient-soft), 
                0 8px 25px var(--rainbow-gradient) !important;
            transform: translateY(-2px) !important;
        }
        
        /* Enhanced Checkbox Styling */
        .form-row input[type="checkbox"] {
            width: 22px !important;
            height: 22px !important;
            border-radius: 8px !important;
            border: 3px solid !important;
            border-color: var(--rainbow-gradient) !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
            
            /* border-image: var(--rainbow-gradient) 1 !important; */
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            position: relative !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            background: #ffffff !important;
        }
        
        .form-row input[type="checkbox"]:hover {
            border-width: 4px !important;
            box-shadow: 0 6px 20px var(--rainbow-gradient-soft) !important;
            transform: scale(1.1) !important;
        }
        
        .form-row input[type="checkbox"]:checked {
            background: var(--rainbow-gradient) !important;
            border-color: transparent !important;
            box-shadow: 0 0 20px var(--rainbow-gradient-soft) !important;
            transform: scale(1.1) !important;
        }
        
        .form-row input[type="checkbox"]:checked::after {
            content: '✓' !important;
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            color: white !important;
            font-size: 14px !important;
            font-weight: bold !important;
        }
        
        /* Enhanced Select Styling */
        .form-row select {
            border-radius: 12px !important;
            border: 2px solid !important;
            /* border-image: var(--rainbow-gradient) 1 !important; */
            box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
            background: #ffffff !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 16px !important;
            padding-right: 40px !important;
        }
        
        /* Global Border Radius for all elements */
        .wrap * {
            border-radius: 8px !important;
        }
        
        /* Override for specific elements that need different radius */
        .wrap .button,
        .wrap .button-primary,
        .wrap .button-secondary,
        .wrap input[type="submit"] {
            border-radius: 20px !important;
        }
        
        .nav-tab {
            border-radius: 12px !important;
        }
        
        .wrap,
        .tab-content,
        .nav-tab-wrapper {
            border-radius: 16px !important;
        }
        
        .lang-tab-btn,
        .dynamic-button-tab {
            border-radius: 10px !important;
        }
        
        .media-upload-button {
            border-radius: 15px !important;
        }
        
        .icon-popup-close {
            border-radius: 50% !important;
        }
        
        .icon-grid-item {
            border-radius: 12px !important;
        }
        
        .color-picker-input {
            border-radius: 10px !important;
            height: 35px !important;
        }
        
        .form-row input[type="checkbox"] {
            border-radius: 6px !important;
        }
        
        /* Enhanced form elements with better radius */
        .form-row input[type="text"],
        .form-row input[type="url"],
        .form-row input[type="email"],
        .form-row input[type="number"],
        .form-row textarea {
            border-radius: 12px !important;
        }
        
        /* Button radius variations */
        .wrap .button.delete,
        .wrap .button-danger {
            border-radius: 18px !important;
        }
        
        .wrap .button[name="reset_all_tabs"],
        .wrap .button.reset {
            border-radius: 18px !important;
        }
        
        /* Force border radius on all elements (except div and form elements) */
        .wrap span, .wrap p, .wrap h1, .wrap h2, .wrap h3, .wrap h4, .wrap h5, .wrap h6, .wrap label, .wrap a, .wrap img, .wrap table, .wrap tr, .wrap td, .wrap th {
            border-radius: 8px !important;
        }
        
        /* Specific overrides - only for buttons and tabs */
        .nav-tab {
            border-radius: 12px !important;
            border: 2px solid #ffffff !important;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient) !important;
        }
        
        .lang-tab-btn {
            border-radius: 10px !important;
            border: 2px solid #ffffff !important;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient) !important;
        }
        
        .dynamic-button-tab {
            border-radius: 10px !important;
            border: 2px solid #ffffff !important;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient) !important;
            padding: 15px 30px;
            background: #ffffff !important;
            background-clip: border-box;
            color: var(--text-secondary);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }
        
        /* Specific Border Radius for different elements */
        .wrap,
        .tab-content,
        .nav-tab-wrapper {
            border-radius: 16px !important;
        }
        
        .nav-tab {
            border-radius: 12px !important;
        }
        
        .lang-tab-btn,
        .dynamic-button-tab {
            border-radius: 10px !important;
        }
        
        .media-upload-button {
            border-radius: 15px !important;
        }
        
        .icon-popup-close {
            border-radius: 50% !important;
        }
        
        .icon-grid-item {
            border-radius: 12px !important;
        }
        
        .color-picker-input {
            border-radius: 10px !important;
            height: 35px !important;        }
        
        .form-row input[type="checkbox"] {
            border-radius: 6px !important;
        }
        
        /* Enhanced form elements with better radius */
        .form-row input[type="text"],
        .form-row input[type="url"],
        .form-row input[type="email"],
        .form-row input[type="number"],
        .form-row textarea {
            border-radius: 12px !important;
        }
        
        /* Button radius variations */
        .wrap .button,
        .wrap .button-primary,
        .wrap .button-secondary,
        .wrap input[type="submit"] {
            border-radius: 20px !important;
        }
        
        .wrap .button.delete,
        .wrap .button-danger {
            border-radius: 18px !important;
        }
        
        .wrap .button[name="reset_all_tabs"],
        .wrap .button.reset {
            border-radius: 18px !important;
        }
        
        .form-row select:hover {
            border-width: 3px !important;
            box-shadow: 0 6px 20px var(--rainbow-gradient-soft) !important;
            transform: translateY(-2px) !important;
        }
        
        .form-row select:focus {
            outline: none !important;
            border-width: 3px !important;
            box-shadow: 0 0 0 4px var(--rainbow-gradient-soft), 0 8px 25px var(--rainbow-gradient-soft) !important;
        }

        .wrap::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--rainbow-gradient);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }


        .wrap h1 {
            font-size: 32px;
            margin-bottom: 30px;
            color: var(--text-primary);
            text-align: center;
            background: var(--rainbow-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            position: relative;
        }

        .wrap h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--rainbow-gradient);
            border-radius: 2px;
        }

        .wrap h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: var(--text-primary);
            border-bottom: 2px solid transparent;
            background: var(--rainbow-gradient);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            padding-bottom: 10px;
            font-weight: 600;
            width: 220px;

        }

        /* Appearance settings - Gradient texts */
        /* #appearance h2 {
            background: var(--rainbow-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            text-align: left !important;
            margin: 30px 0 20px 0;
        } */
        .wrap [dir="rtl"] h2 {
            text-align: right !important;
            background: var(--rainbow-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            margin: 30px 0 20px 0;
            width: 220px;


        }
        .wrap #appearance h2::after {
            content: '';
            display: block;
            width: 220px;
            height: 3px;
            background: var(--rainbow-gradient) !important;
            margin: 15px auto 0 auto;
            border-radius: 2px;
        }
        .wrap [dir="rtl"] h2::after {
            left: auto !important;
            right: 0 !important;
            background: var(--rainbow-gradient) !important;
            border-radius: 2px;
            content: '';
            display: block;
            width: 220px;
            height: 3px;

        }


        /* Normal buttons - White background */
        .wrap .media-upload-button,
        .wrap .dynamic-button-tab,
        .wrap .lang-tab-btn {
            background: #ffffff !important;
            border: 2px solid transparent;
            background-clip: border-box;
            color: var(--text-secondary);
        }

        .wrap .media-upload-button::before,
        .wrap .dynamic-button-tab::before,
        .wrap .lang-tab-btn::before {
            background: #ffffff !important;
        }

        .wrap .media-upload-button.active,
        .wrap .dynamic-button-tab.active,
        .wrap .lang-tab-btn.active {
            background: var(--rainbow-gradient-soft) !important;
            border-color: transparent;
            color: var(--text-primary);
        }

        .wrap .media-upload-button.active::before,
        .wrap .dynamic-button-tab.active::before,
        .wrap .lang-tab-btn.active::before {
            background: var(--rainbow-gradient-soft) !important;
        }

        .nav-tab-wrapper {
            margin-bottom: 30px;
            border-bottom: 3px solid transparent;
            background: var(--rainbow-gradient);
            background-size: 200% 200%;
            animation: rainbow-shift 3s ease-in-out infinite;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 5px 5px 0 5px;
        }

        @keyframes rainbow-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .nav-tab {
            background: var(--primary-bg);
            border: 2px solid #ffffff;
            color: var(--text-secondary);
            font-weight: 500;
            margin-right: 8px;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                var(--shadow-soft) !important;
        }

        .nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--rainbow-gradient);
            transition: var(--transition);
            z-index: -1;
        }

        .nav-tab:hover {
            color: var(--primary-bg);
            transform: translateY(-2px);
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 3px var(--rainbow-gradient),
                var(--shadow-medium) !important;
        }

        .nav-tab:hover::before {
            left: 0;
}

        .nav-tab.active {
            background: var(--rainbow-gradient);
            color: var(--primary-bg);
            transform: translateY(-2px);
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 3px var(--rainbow-gradient),
                var(--shadow-medium) !important;
            font-weight: 600;
        }

        .tab-content {
            display: none;
            background: var(--primary-bg);
            border: none;
            border-radius: var(--border-radius);
            padding: 30px;
    margin-top: -1px;
            box-shadow: var(--shadow-medium);
            position: relative;
            overflow: hidden;
            transform: translateY(20px);
            transition: var(--transition);
        }

        .tab-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--rainbow-gradient);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .tab-content.active {
            display: block !important;
            opacity: 1;
            transform: translateY(0);
            animation: slideIn 0.5s ease-out;
        }

        .tab-content:not(.active) {
            display: none !important;
            opacity: 0 !important;
            transform: translateY(20px) !important;
            pointer-events: none !important;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-row {
            margin-bottom: 20px;
            position: relative;
        }

        .form-row label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width:230px;

        }

        /* Modern Input Styling */
.wrap input[type="text"],
.wrap input[type="email"],
.wrap input[type="url"],
.wrap input[type="password"],
.wrap select,
.wrap textarea {
    width: 100%;
            padding: 15px 20px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
    font-size: 14px;
            line-height: 1.5;
            transition: var(--transition);
            background: var(--primary-bg);
            color: var(--text-primary);
            box-shadow: var(--shadow-soft);
            position: relative;
        }

        .wrap input[type="text"]:focus,
        .wrap input[type="email"]:focus,
        .wrap input[type="url"]:focus,
        .wrap input[type="password"]:focus,
        .wrap select:focus,
        .wrap textarea:focus {
            outline: none;
            border-color: transparent;
            background: var(--rainbow-gradient-soft);
            box-shadow: 0 0 0 4px var(--rainbow-gradient-soft);
            transform: translateY(-2px);
}

        .form-row input[type="text"],
        .form-row input[type="color"],
        .form-row select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 200px;
        }

        .form-row input[type="checkbox"] {
            margin: 0;
        }
        .wrap input[type="text"]:focus,
.wrap input[type="email"]:focus,
.wrap input[type="url"]:focus,
.wrap input[type="password"]:focus,
.wrap select:focus,
.wrap textarea:focus {
    outline: none;
    border-color: #2271b1;
    box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
    transform: translateY(-1px);
}


        .copy-shortcode {
            margin-top: 20px;
            padding: 10px;
            background-color: #eef5fb;
            border: 1px dashed #007cba;
            border-radius: 5px;
        }

        .copy-shortcode textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            font-family: monospace;
            resize: none;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .copy-shortcode button {
            background-color: #007cba;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .copy-shortcode button:hover {
            background-color: #005a9e;
        }

        .copy-success {
            color: green;
            font-size: 12px;
            margin-top: 5px;
        }
        
        /* Enhanced Reset Button Styling */
        .enhanced-reset-button {
            background: linear-gradient(135deg, #fff3cd, #ffe4b5) !important;
            color: #b8860b !important;
            border: 2px solid #ffd700 !important;
            padding: 12px 24px !important;
            border-radius: 8px !important;
            cursor: pointer !important;
            font-weight: bold !important;
            font-size: 14px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            text-transform: none !important;
            box-shadow: 0 4px 6px -1px rgba(184, 134, 11, 0.1) !important;
        }
        
        .enhanced-reset-button:hover {
            background: linear-gradient(135deg, #ffe4b5, #ffd700) !important;
            color: #8b6914 !important;
            border-color: #daa520 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 15px -3px rgba(184, 134, 11, 0.2) !important;
        }
        
        .enhanced-reset-button:active {
            transform: translateY(0) !important;
            box-shadow: 0 4px 6px -1px rgba(184, 134, 11, 0.1) !important;
        }

        /* Color Preview Styles */
        .color-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;

        }

        .color-preview {
            width: 40px;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 4px;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .color-preview:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

        .color-code {
            font-family: monospace;
            font-size: 12px;
            color: #666;
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            display: none;
        }

        /* Language Tabs */
        .lang-tab-btn {
            background: #fefefe;
            border: 2px solid #ffffff;
            padding: 12px 24px;
            margin-right: 8px;
            cursor: pointer;
            border-radius: 20px;
            transition: var(--transition);
            font-weight: 500;
            color: var(--text-secondary);
            position: relative;
            overflow: hidden;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient) !important;
        }
        .dynamic-button-color-preview {
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
            margin-left: 10px;
        }
        .lang-tab-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #fefefe;
            border-radius: 18px;
            z-index: -1;
        }
        
        .lang-tab-btn:hover {
            transform: translateY(-2px);
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 3px var(--rainbow-gradient),
                0 8px 25px rgba(255, 107, 107, 0.3), 
                0 4px 15px rgba(254, 202, 87, 0.3), 
                0 2px 8px rgba(72, 219, 251, 0.3) !important;
        }

        .lang-tab-btn.active {
            background: var(--rainbow-gradient-soft);
            border-color: #ffffff;
            color: var(--text-primary);
            font-weight: 600;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 3px var(--rainbow-gradient),
                var(--shadow-medium) !important;
            transform: translateY(-2px);
        }

        .lang-tab-btn.active::before {
            background: var(--rainbow-gradient-soft);
        }

        .lang-content {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 0 8px 8px 8px;
            background: #fff;
        }
        
        /* Static Language Content */
        .static-lang-content {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 0 8px 8px 8px;
            background: #fff;
        }
        
        /* Dynamic Language Content */
        .dynamic-lang-content {
            border: 1px solid #e1e5e9;
            padding: 15px;
            border-radius: 6px;
            background: #f8f9fa;
            margin-top: 10px;
            display: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }
        
        .dynamic-lang-content.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
            animation: fadeIn 0.3s ease-in;
        }
        
        .dynamic-lang-content h5 {
            margin: 0 0 15px 0;
            color: #403199;
            font-size: 14px;
            border-bottom: 2px solid #403199;
            padding-bottom: 8px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
/* Dynamic Button Selector Styles */
.dynamic-button-selector {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 30px;
    padding: 25px;
    background: var(--secondary-bg);
    border-radius: var(--border-radius);
    border: 2px solid var(--border-color);
    position: relative;
    overflow: hidden;
}

.dynamic-button-selector::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--rainbow-gradient);
    opacity: 0.02;
    z-index: -1;
}

.wrap .dynamic-button-tab {
    padding: 15px 30px;
    background: #fefefe;
    border: 2px solid transparent;
    background: var(--rainbow-gradient);
    background-clip: border-box;
    border-radius: 30px;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    font-size: 14px;
    min-width: 140px;
    text-align: center;
    box-shadow: var(--shadow-soft);
    position: relative;
    overflow: hidden;
}

.dynamic-button-tab::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #fefefe;
    border-radius: 28px;
    z-index: -1;
}

.dynamic-button-tab:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3), 0 4px 15px rgba(254, 202, 87, 0.3), 0 2px 8px rgba(72, 219, 251, 0.3);
}

.dynamic-button-tab.active {
    background: var(--rainbow-gradient-soft);
    border-color: transparent;
    color: var(--text-primary);
    transform: translateY(-3px);
    box-shadow: var(--shadow-medium);
    font-weight: 700;
}

.dynamic-button-tab.active::before {
    background: var(--rainbow-gradient-soft);
}

.dynamic-button-settings {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.dynamic-button-settings.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

        /* New fields */
        .media-upload-field {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            padding: 20px;
            background: var(--secondary-bg);
            border-radius: var(--border-radius);
            border: 2px solid var(--border-color);
            transition: var(--transition);
        }

        .media-upload-field:hover {
            background: var(--primary-bg);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            border-color: transparent;
            background: var(--rainbow-gradient-soft);
        }
        
        .media-upload-field input[type="text"] {
            flex: 1;
            min-width: 200px;
        }
        
        .media-upload-button {
            background: #fefefe;
            color: var(--text-primary);
            border: 2px solid transparent;
            background: var(--rainbow-gradient);
            background-clip: border-box;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: var(--transition);
            white-space: nowrap;
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }

        .media-upload-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #fefefe;
            border-radius: 23px;
            z-index: -1;
        }

        .media-upload-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3), 0 4px 15px rgba(254, 202, 87, 0.3), 0 2px 8px rgba(72, 219, 251, 0.3);
        }

        .media-upload-button.active {
            background: var(--rainbow-gradient-soft);
            border-color: transparent;
        }

        .media-upload-button.active::before {
            background: var(--rainbow-gradient-soft);
        }
        
        .media-upload-preview {
            margin-left: 10px;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            background: var(--primary-bg);
            min-height: 50px;
            min-width: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .media-upload-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--rainbow-gradient-soft);
            opacity: 0;
            transition: var(--transition);
            z-index: -1;
        }

        .media-upload-preview:hover::before {
            opacity: 1;
        }
        
        .media-upload-preview img {
            max-width: 60px;
            max-height: 40px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            object-fit: cover;
            transition: var(--transition);
        }

        .media-upload-preview:hover img {
            transform: scale(1.1);
        }
        
        .media-upload-preview i {
            font-size: 28px;
            background: var(--rainbow-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: var(--transition);
        }

        .media-upload-preview:hover i {
            transform: scale(1.2);
        }
        
        .media-upload-preview:empty {
            background: var(--rainbow-gradient-soft);
            border-style: dashed;
            border-color: var(--border-color);
        }
        
        .media-upload-preview:empty::before {
            content: 'پیش‌نمایش';
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 500;
        }
        
        .color-picker-field {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
            padding: 15px;
            background: var(--secondary-bg);
            border-radius: var(--border-radius);
            border: 2px solid var(--border-color);
            transition: var(--transition);
        }

        .color-picker-field:hover {
            background: var(--primary-bg);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            border-color: transparent;
            background: var(--rainbow-gradient-soft);
        }
        
        .color-picker-input {
            width: 100px !important;
            border-radius: 10px !important;
            height: 35px !important;
            padding: 0;
            border: 2px solid #ffffff;
            /* border-radius: 20px; */
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient) !important;
        }

        .color-picker-input:hover {
            border-color: #ffffff;
            box-shadow: 
                inset 0 0 0 1px var(--rainbow-gradient),
                0 0 0 3px var(--rainbow-gradient),
                0 8px 25px var(--rainbow-gradient-soft) !important;
            transform: scale(1.05);
        }
        
        /* Icon Popup Styles */
        .icon-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease-out;
        }
        
        .icon-popup-content {
            background: var(--primary-bg);
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            overflow: hidden;
            box-shadow: var(--shadow-large);
            border: 3px solid transparent;
            background-clip: padding-box;
            position: relative;
        }

        .icon-popup-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--rainbow-gradient);
            border-radius: var(--border-radius);
            z-index: -1;
            opacity: 0.1;
        }
        
        .icon-popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            border-bottom: 2px solid var(--border-color);
            background: var(--rainbow-gradient-soft);
            position: relative;
        }

        .icon-popup-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--rainbow-gradient);
        }
        
        .icon-popup-header h3 {
            margin: 0;
            color: var(--text-primary);
            font-size: 24px;
            font-weight: 700;
            background: var(--rainbow-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .icon-popup-close {
            background: var(--rainbow-gradient);
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--primary-bg);
            padding: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
        }
        
        .icon-popup-close:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: var(--shadow-medium);
        }
        
        .icon-popup-body {
            padding: 30px;
        }
        
        .icon-search {
            margin-bottom: 25px;
        }
        
        .icon-search input {
            width: 100%;
            padding: 18px 20px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            background: var(--primary-bg);
            box-shadow: var(--shadow-soft);
        }
        
        .icon-search input:focus {
            outline: none;
            border-color: transparent;
            background: var(--rainbow-gradient-soft);
            box-shadow: 0 0 0 4px var(--rainbow-gradient-soft);
            transform: translateY(-2px);
        }
        
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 20px;
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
            scrollbar-width: thin;
            scrollbar-color: var(--border-color) transparent;
        }

        .icon-grid::-webkit-scrollbar {
            width: 8px;
        }

        .icon-grid::-webkit-scrollbar-track {
            background: var(--rainbow-gradient-soft);
            border-radius: 4px;
        }

        .icon-grid::-webkit-scrollbar-thumb {
            background: var(--rainbow-gradient);
            border-radius: 4px;
            border: 2px solid var(--primary-bg);
        }

        .icon-grid::-webkit-scrollbar-thumb:hover {
            background: var(--rainbow-gradient-hover);
        }
        
        .icon-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 25px 20px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            background: var(--primary-bg);
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }

        .icon-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--rainbow-gradient);
            opacity: 0;
            transition: var(--transition);
            z-index: -1;
        }
        
        .icon-item:hover {
            border-color: transparent;
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .icon-item:hover::before {
            opacity: 0.1;
        }
        
        .icon-item.selected {
            border-color: transparent;
            background: var(--rainbow-gradient-soft);
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }

        .icon-item.selected::before {
            opacity: 0.2;
        }
        
        .icon-item i {
            font-size: 36px;
            margin-bottom: 12px;
            background: var(--rainbow-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: var(--transition);
        }

        .icon-item:hover i {
            transform: scale(1.2);
        }
        
        .icon-item span {
            font-size: 13px;
            color: var(--text-secondary);
            text-align: center;
            word-break: break-word;
            font-weight: 500;
            transition: var(--transition);
        }

        .icon-item:hover span {
            color: var(--text-primary);
            font-weight: 600;
        }


    /* max-width: 100%;
    max-height: 100%;
    object-fit: cover;
} */

.media-upload-button {
    padding: 10px 20px;
    background: #007bff;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.media-upload-button:hover {
    background: #0056b3;
    transform: translateY(-1px);
}

/* Color Picker Enhancement */
.color-picker-field {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
    padding: 15px;
    
}

.color-picker-input {
    width: 60px !important;
    /* height: 40px !important; */
    border-radius: 10px !important;
    height: 35px !important;
    padding: 0 !important;
    border: none !important;
    border-radius: 6px !important;
    cursor: pointer;
}

/* Language Tabs Enhancement */
.lang-tab-btn {
    padding: 8px 16px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-right: 5px;
    margin-bottom: 10px;
    font-size: 13px;
}

.lang-tab-btn.active {
    background: #007bff;
    border-color: #007bff;
    color: #ffffff;
}

.lang-tab-btn:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dynamic-button-selector {
        flex-direction: column;
        gap: 8px;
    }
    
    .dynamic-button-tab {
        min-width: auto;
        width: 100%;
        padding: 15px 30px;
    background: #fefefe;
    border: 2px solid transparent;
    background: var(--rainbow-gradient);
    background-clip: border-box;
    border-radius: 30px;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    }
    
    .media-upload-field {
        flex-direction: column;
        align-items: flex-start;
    }
}
        /* Dynamic Button Settings */
        .dynamic-button-settings {
            transition: all 0.3s ease;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            background: linear-gradient(135deg, #f9f9f9, #ffffff);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .dynamic-button-settings:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .dynamic-button-settings h3 {
            color: #403199;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            font-size: 18px;
        }
        
        .language-tabs {
            margin: 20px 0;
        }
        
        .language-tabs h4 {
            color: #666;
            margin: 0 0 15px 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .wrap textarea {
    resize: vertical;
    min-height: 80px;
    font-family: inherit;
}

/* Select Styling */
.wrap select {
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23666" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 20px;
    padding-right: 40px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

        /* Font and Background Options */
        .wrap #custom-font-row small {
            color: #666;
            font-style: italic;
        }
        /* Checkbox Styling */
.wrap input[type="checkbox"] {
    width: 18px;
    height: 18px;
    border: 2px solid #2271b1;
    border-radius: 4px;
    margin-right: 10px;
    vertical-align: middle;
    cursor: pointer;
}

.wrap input[type="checkbox"]:checked {
    background-color: #2271b1;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23fff" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>');
    background-repeat: no-repeat;
    background-position: center;
}

/* Button Styling - All buttons with white background and rainbow borders */
.wrap .button,
.wrap .button-primary,
.wrap .button-secondary {
    background: #ffffff;
    border: 3px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 25px;
    color: #333;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    padding: 18px 36px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    line-height: 1.4;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Hover effect for all buttons - rainbow shadow */
.wrap .button:hover,
.wrap .button-primary:hover,
.wrap .button-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px var(--rainbow-gradient);
    border-width: 4px;
}

/* Active state for all buttons */
.wrap .button:active,
.wrap .button-primary:active,
.wrap .button-secondary:active {
    transform: translateY(-1px);
}

/* Selected/Active state for buttons */
.wrap .button.active,
.wrap .button-primary.active,
.wrap .button-secondary.active {
    background: var(--rainbow-gradient-soft);
    border: 4px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    box-shadow: 0 0 20px var(--rainbow-gradient-soft);
}

/* Special Button Styling - Save/Submit buttons (Blue) */
.wrap .button[type="submit"],
.wrap .button-primary,
.wrap input[type="submit"] {
    background: rgba(52, 152, 219, 0.1);
    border: 3px solid #3498db;
    color: #2980b9;
    font-weight: 700;
}

.wrap .button[type="submit"]:hover,
.wrap .button-primary:hover,
.wrap input[type="submit"]:hover {
    background: rgba(52, 152, 219, 0.2);
    border-color: #2980b9;
    box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
}

/* Special Button Styling - Delete buttons (Red) */
.wrap .button.delete,
.wrap .button-danger,
.wrap button[onclick*="delete"] {
    background: rgba(231, 76, 60, 0.1);
    border: 3px solid #e74c3c;
    color: #c0392b;
    font-weight: 700;
}

.wrap .button.delete:hover,
.wrap .button-danger:hover,
.wrap button[onclick*="delete"]:hover {
    background: rgba(231, 76, 60, 0.2);
    border-color: #c0392b;
    box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
}

/* Special Button Styling - Reset buttons (Green) */
.wrap .button[name="reset_all_tabs"],
.wrap .button.reset,
.wrap button[onclick*="reset"] {
    background: rgba(46, 204, 113, 0.1);
    border: 3px solid #2ecc71;
    color: #27ae60;
    font-weight: 700;
}

.wrap .button[name="reset_all_tabs"]:hover,
.wrap .button.reset:hover,
.wrap button[onclick*="reset"]:hover {
    background: rgba(46, 204, 113, 0.2);
    border-color: #27ae60;
    box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
}

/* Enhanced Button Styling for better rainbow borders */
.wrap .button,
.wrap .button-primary,
.wrap .button-secondary {
    border-image-slice: 1;
    border-image-source: var(--rainbow-gradient);
}

/* Alternative rainbow border method for better browser support */
.wrap .button::before,
.wrap .button-primary::before,
.wrap .button-secondary::before {
    content: '';
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    background: var(--rainbow-gradient);
    border-radius: 28px;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.wrap .button:hover::before,
.wrap .button-primary:hover::before,
.wrap .button-secondary:hover::before {
    opacity: 1;
}

/* Language Tab Button Styling */
.lang-tab-btn {
    background: #ffffff !important;
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 15px;
    color: #333;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    margin: 0 5px 5px 0;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    position: relative;
    overflow: hidden;
}

.lang-tab-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--rainbow-gradient);
    border-width: 3px;
}

.lang-tab-btn.active {
    background: var(--rainbow-gradient-soft);
    border: 3px solid;
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    /* border-image: var(--rainbow-gradient) 1; */
    color: #333;
    font-weight: 700;
    box-shadow: 0 0 15px var(--rainbow-gradient-soft);
}

/* Dynamic Button Tab Styling */
.dynamic-button-tab {
    background: #ffffff !important;
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 15px;
    color: #333;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    margin: 0 5px 5px 0;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    position: relative;
    overflow: hidden;
}

.dynamic-button-tab:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--rainbow-gradient);
    border-width: 3px;
}

.dynamic-button-tab.active {
    background: var(--rainbow-gradient-soft);
    border: 3px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    color: #333;
    font-weight: 700;
    box-shadow: 0 0 15px var(--rainbow-gradient-soft);
}

/* Media Upload Button Styling */
.media-upload-button {
    background: #ffffff !important;
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 20px;
    color: #333;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    padding: 8px 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.media-upload-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--rainbow-gradient);
    border-width: 3px;
}

/* Icon Popup Button Styling */
.icon-popup-close {
    background: #ffffff;
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 50%;
    color: #333;
    cursor: pointer;
    font-size: 18px;
    font-weight: 600;
    width: 30px;
    height: 30px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.icon-popup-close:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px var(--rainbow-gradient);
    border-width: 3px;
}

/* Additional Button Types Styling */
.wrap button[type="button"]:not(.lang-tab-btn):not(.dynamic-button-tab):not(.media-upload-button):not(.icon-popup-close) {
    background: #ffffff;
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 20px;
    color: #333;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.wrap button[type="button"]:not(.lang-tab-btn):not(.dynamic-button-tab):not(.media-upload-button):not(.icon-popup-close):hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--rainbow-gradient);
    border-width: 3px;
}

/* Form Submit Buttons */
.wrap input[type="submit"]:not(.button):not(.button-primary):not(.button-secondary) {
    background: rgba(52, 152, 219, 0.1);
    border: 3px solid #3498db;
    border-radius: 20px;
    color: #2980b9;
    cursor: pointer;
    font-size: 14px;
    font-weight: 700;
    padding: 12px 24px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.wrap input[type="submit"]:not(.button):not(.button-primary):not(.button-secondary):hover {
    background: rgba(52, 152, 219, 0.2);
    border-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
}

/* Focus States for Accessibility */
.wrap .button:focus,
.wrap .button-primary:focus,
.wrap .button-secondary:focus,
.lang-tab-btn:focus,
.dynamic-button-tab:focus,
.media-upload-button:focus,
.icon-popup-close:focus,
.wrap button[type="button"]:focus,
.wrap input[type="submit"]:focus {
    outline: none;
    box-shadow: 0 0 0 3px var(--rainbow-gradient-soft), 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Disabled State Styling */
.wrap .button:disabled,
.wrap .button-primary:disabled,
.wrap .button-secondary:disabled,
.lang-tab-btn:disabled,
.dynamic-button-tab:disabled,
.media-upload-button:disabled,
.icon-popup-close:disabled,
.wrap button[type="button"]:disabled,
.wrap input[type="submit"]:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
}

/* Icon Grid Item Styling */
.icon-grid-item {
    background: #ffffff;
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 15px;
    color: #333;
    cursor: pointer;
    font-size: 24px;
    padding: 15px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    min-width: 60px;
    min-height: 60px;
}

.icon-grid-item:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px var(--rainbow-gradient);
    border-width: 3px;
}

.icon-grid-item.selected {
    background: var(--rainbow-gradient-soft);
    border: 3px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    color: #333;
    font-weight: 700;
    box-shadow: 0 0 20px var(--rainbow-gradient-soft);
}

/* Color Picker Field Styling */
.color-picker-input {
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 10px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-picker-input:hover {
    border-width: 3px;
    box-shadow: 0 4px 15px var(--rainbow-gradient-soft);
}

.color-picker-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px var(--rainbow-gradient-soft), 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Form Input Styling */
.form-row input[type="text"],
.form-row input[type="url"],
.form-row input[type="email"],
.form-row textarea {
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 10px;
    padding: 12px 15px;
    transition: all 0.3s ease;
    background: #ffffff;
}

.form-row input[type="text"]:hover,
.form-row input[type="url"]:hover,
.form-row input[type="email"]:hover,
.form-row textarea:hover {
    border-width: 3px;
    box-shadow: 0 4px 15px var(--rainbow-gradient-soft);
}

.form-row input[type="text"]:focus,
.form-row input[type="url"]:focus,
.form-row input[type="email"]:focus,
.form-row textarea:focus {
    outline: none;
    border-width: 3px;
    box-shadow: 0 0 0 3px var(--rainbow-gradient-soft), 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Checkbox Styling */
.form-row input[type="checkbox"] {
    border: 2px solid;
    /* border-image: var(--rainbow-gradient) 1; */
    box-shadow: 
                inset 0 0 0 2px var(--rainbow-gradient),
                0 0 0 2px var(--rainbow-gradient),
                0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border-radius: 5px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-row input[type="checkbox"]:hover {
    border-width: 3px;
    box-shadow: 0 4px 15px var(--rainbow-gradient-soft);
}

.form-row input[type="checkbox"]:checked {
    background: var(--rainbow-gradient);
    border-color: transparent;
}

/* Language Tab Styling */
.language-tabs {
            background: var(--secondary-bg);
            border-radius: var(--border-radius);
            padding: 25px;
    margin-bottom: 25px;
            border: 2px solid var(--border-color);
            transition: var(--transition);
        }

        .language-tabs:hover {
            background: var(--rainbow-gradient-soft);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
}

.language-tabs h3,
.language-tabs h4 {
    margin-top: 0;
            color: var(--text-primary);
    font-size: 18px;
    margin-bottom: 15px;
            background: var(--rainbow-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
        }
}



/* Language Content Styling */
.lang-content,
.static-lang-content,
.dynamic-lang-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #2271b1;
}

.lang-content h3,
.static-lang-content h3,
.dynamic-lang-content h5 {
    margin-top: 0;
    color: #1d2327;
    font-size: 16px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.lang-content h3::before,
.static-lang-content h3::before,
.dynamic-lang-content h5::before {
    content: '';
    width: 20px;
    height: 20px;
    margin-right: 10px;
    background-size: contain;
    background-repeat: no-repeat;
}

/* Dynamic Button Settings Styling */
.dynamic-button-settings {
    background: #fff;
    border: 2px solid #e1e1e1;
    border-radius: 12px;
    padding: 25px;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.dynamic-button-settings:hover {
    border-color: #2271b1;
    box-shadow: 0 4px 16px rgba(34, 113, 177, 0.15);
}

.dynamic-button-settings h3 {
    margin-top: 0;
    color: #2271b1;
    font-size: 18px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e1e1e1;
}

/* Section Headers */
.wrap h2 {
    color: #1d2327;
    font-size: 24px;
    margin: 30px 0 20px 0;
    padding-bottom: 10px;
    /* border-bottom: 3px solid #2271b1; */
    position: relative;
    width: 220px;

}

.wrap h2::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 220px;
    height: 3px;
    background: var(--rainbow-gradient);
    margin: 15px auto 0 auto;
    border-radius: 2px;

}

.wrap [dir="rtl"] h2::after {
    left: auto;
    right: 0; /* Align underline to the right for RTL languages */
    background: var(--rainbow-gradient) !important;
    border-radius: 2px;
    content: '';
    width: 220px;
    height: 3px;
    background: var(--rainbow-gradient) !important;
    margin: 15px auto 0 auto;
    border-radius: 2px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .tab-content {
        padding: 15px;
    }
    
    .lang-tab-btn {
        display: block;
        width: 100%;
        margin-right: 0;
        margin-bottom: 10px;
        text-align: center;
    }
    
    .dynamic-button-settings {
        padding: 15px;
    }
}

/* Success/Error Messages */
.notice {
    border-radius: 8px;
    border-left: 4px solid;
    padding: 15px 20px;
    margin: 20px 0;
}

.notice-success {
    background: #f0f9ff;
    border-color: #10b981;
    color: #065f46;
}

.notice-error {
    background: #fef2f2;
    border-color: #ef4444;
    color: #991b1b;
}

/* Loading States */
.wrap .button:disabled,
.wrap .button-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
    </style>
    
    <div class="wrap" <?php echo $is_rtl ? 'dir="rtl"' : 'dir="ltr"'; ?> <?php echo $is_rtl ? 'style="text-align: right;"' : 'style="text-align: left;"'; ?>>
        <h1><?php echo esc_html($admin_text['page_title']); ?></h1>
        
        <!-- Navigation Tabs -->
        <nav class="nav-tab-wrapper">
            <a href="?page=QrCode_Settings&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'active' : ''; ?>">
                <?php echo esc_html($admin_text['general_tab']); ?>
            </a>
            <a href="?page=QrCode_Settings&tab=static" class="nav-tab <?php echo $active_tab === 'static' ? 'active' : ''; ?>">
                <?php echo esc_html($admin_text['static_tab']); ?>
            </a>
            <a href="?page=QrCode_Settings&tab=dynamic" class="nav-tab <?php echo $active_tab === 'dynamic' ? 'active' : ''; ?>">
                <?php echo esc_html($admin_text['dynamic_tab']); ?>
            </a>
            <a href="?page=QrCode_Settings&tab=messages" class="nav-tab <?php echo $active_tab === 'messages' ? 'active' : ''; ?>">
                <?php echo esc_html($admin_text['messages_tab']); ?>
            </a>
            <a href="?page=QrCode_Settings&tab=appearance" class="nav-tab <?php echo $active_tab === 'appearance' ? 'active' : ''; ?>">
                <?php echo esc_html($admin_text['appearance_settings']); ?>
            </a>
        </nav>

        <!-- General Tab -->
        <div id="general" class="tab-content <?php echo $active_tab === 'general' ? 'active' : ''; ?>">
        <form method="POST">
                <?php wp_nonce_field('qrcdr_settings_nonce', 'qrcdr_nonce'); ?>
                <input type="hidden" name="active_tab" value="general">
                
                <h2><?php echo esc_html($admin_text['general_settings']); ?></h2>
                <div class="form-row">
                    <label><?php echo esc_html(qrcdr_get_form_text('default_language', $current_lang)); ?></label>
                    <select name="locale">
                        <option value="fa" <?php selected($locale, 'fa'); ?>>فارسی</option>
                        <option value="en" <?php selected($locale, 'en'); ?>>English</option>
                        <option value="ar" <?php selected($locale, 'ar'); ?>>العربية</option>
                        <option value="tr" <?php selected($locale, 'tr'); ?>>Türkçe</option>
                        <option value="ru" <?php selected($locale, 'ru'); ?>>Русский</option>
                        <option value="fr" <?php selected($locale, 'fr'); ?>>Français</option>
                        <option value="de" <?php selected($locale, 'de'); ?>>Deutsch</option>
                    </select>
        </div>
        
        <div class="form-row">
                    <label><?php echo esc_html(qrcdr_get_form_text('google_api_key', $current_lang)); ?></label>
                    <input type="text" name="google_api_key" value="<?php echo esc_attr($google_api_key); ?>" placeholder="<?php echo esc_attr(qrcdr_get_form_text('google_api_key', $current_lang)); ?>" />
        </div>
        
                <div class="form-row two-columns">
                    <div class="input-group">
                        <label><?php echo esc_html(qrcdr_get_form_text('latitude', $current_lang)); ?></label>
                        <input type="number" name="lat" value="<?php echo esc_attr($lat); ?>" placeholder="40.7128" step="0.0001" class="small-input" />
                    </div>
                    <div class="input-group">
                        <label><?php echo esc_html(qrcdr_get_form_text('longitude', $current_lang)); ?></label>
                        <input type="number" name="lng" value="<?php echo esc_attr($lng); ?>" placeholder="-74.0059" step="0.0001" class="small-input" />
                    </div>
        </div>
        
        <h2><?php echo esc_html($admin_text['visibility_settings']); ?></h2>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('show_title', $current_lang)); ?></label>
            <input type="checkbox" name="visible_title" value="1" <?php checked($visible_title, '1'); ?> />
        </div>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('show_text', $current_lang)); ?></label>
            <input type="checkbox" name="visible_text" value="1" <?php checked($visible_text, '1'); ?> />
        </div>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('show_tabs', $current_lang)); ?></label>
            <input type="checkbox" name="visible_tabs" value="1" <?php checked($visible_tabs, '1'); ?> />
        </div>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('show_navigation', $current_lang)); ?></label>
            <input type="checkbox" name="visible_nav_tab" value="1" <?php checked($visible_nav_tab, '1'); ?> />
        </div>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('show_dynamic', $current_lang)); ?></label>
            <input type="checkbox" name="visible_dynamic" value="1" <?php checked($visible_dynamic, '1'); ?> />
        </div>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('show_static', $current_lang)); ?></label>
            <input type="checkbox" name="visible_static" value="1" <?php checked($visible_static, '1'); ?> />
        </div>
        
        <h2><?php echo esc_html($admin_text['tag_settings']); ?></h2>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('title_tag', $current_lang)); ?></label>
            <select name="selected_tag_title">
                <option value="h1" <?php selected($selected_tag_title, 'h1'); ?>>H1</option>
                <option value="h2" <?php selected($selected_tag_title, 'h2'); ?>>H2</option>
                <option value="h3" <?php selected($selected_tag_title, 'h3'); ?>>H3</option>
                <option value="h4" <?php selected($selected_tag_title, 'h4'); ?>>H4</option>
                <option value="h5" <?php selected($selected_tag_title, 'h5'); ?>>H5</option>
                <option value="h6" <?php selected($selected_tag_title, 'h6'); ?>>H6</option>
                <option value="div" <?php selected($selected_tag_title, 'div'); ?>>DIV</option>
                <option value="p" <?php selected($selected_tag_title, 'p'); ?>>P</option>
            </select>
        </div>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('text_tag', $current_lang)); ?></label>
            <select name="selected_tag_text">
                <option value="h1" <?php selected($selected_tag_text, 'h1'); ?>>H1</option>
                <option value="h2" <?php selected($selected_tag_text, 'h2'); ?>>H2</option>
                <option value="h3" <?php selected($selected_tag_text, 'h3'); ?>>H3</option>
                <option value="h4" <?php selected($selected_tag_text, 'h4'); ?>>H4</option>
                <option value="h5" <?php selected($selected_tag_text, 'h5'); ?>>H5</option>
                <option value="h6" <?php selected($selected_tag_text, 'h6'); ?>>H6</option>
                <option value="div" <?php selected($selected_tag_text, 'div'); ?>>DIV</option>
                <option value="p" <?php selected($selected_tag_text, 'p'); ?>>P</option>
            </select>
        </div>
        
        <div class="form-row">
            <label><?php echo esc_html(qrcdr_get_form_text('tab_tag', $current_lang)); ?></label>
            <select name="selected_tag_tab">
                <option value="h1" <?php selected($selected_tag_tab, 'h1'); ?>>H1</option>
                <option value="h2" <?php selected($selected_tag_tab, 'h2'); ?>>H2</option>
                <option value="h3" <?php selected($selected_tag_tab, 'h3'); ?>>H3</option>
                <option value="h4" <?php selected($selected_tag_tab, 'h4'); ?>>H4</option>
                <option value="h5" <?php selected($selected_tag_tab, 'h5'); ?>>H5</option>
                <option value="h6" <?php selected($selected_tag_tab, 'h6'); ?>>H6</option>
                <option value="div" <?php selected($selected_tag_tab, 'div'); ?>>DIV</option>
                <option value="p" <?php selected($selected_tag_tab, 'p'); ?>>P</option>
            </select>
        </div>
        <h2><?php echo $current_lang === 'fa' ? 'متن‌های قابل تنظیم' : ($current_lang === 'ar' ? 'النصوص القابلة للتعديل' : 'Configurable Texts'); ?></h2>
                <p><em><?php echo $current_lang === 'fa' ? 'متن‌های نمایش داده شده در پلاگین برای هر زبان:' : ($current_lang === 'ar' ? 'النصوص المعروضة في الإضافة لكل لغة:' : 'Texts displayed in the plugin for each language:'); ?></em></p>
                
                <div class="language-tabs">
                    <h4><?php echo $current_lang === 'fa' ? 'تب زبان‌ها' : ($current_lang === 'ar' ? 'تبويبات اللغات' : 'Language Tabs'); ?></h3>
                    <div style="margin-bottom: 20px;">
                
                            <button type="button" class="lang-tab-btn active" onclick="showStaticLanguageTab('fa', event)">فارسی</button>
                            <button type="button" class="lang-tab-btn" onclick="showStaticLanguageTab('en', event)">English</button>
                            <button type="button" class="lang-tab-btn" onclick="showStaticLanguageTab('ar', event)">العربية</button>
                            <button type="button" class="lang-tab-btn" onclick="showStaticLanguageTab('tr', event)">Türkçe</button>
                            <button type="button" class="lang-tab-btn" onclick="showStaticLanguageTab('ru', event)">Русский</button>
                            <button type="button" class="lang-tab-btn" onclick="showStaticLanguageTab('fr', event)">Français</button>
                            <button type="button" class="lang-tab-btn" onclick="showStaticLanguageTab('de', event)">Deutsch</button>
                            </div>
                </div>
                
                <!-- Persian Static Texts -->
                <div id="static-lang-fa" class="static-lang-content active">
                <h3>🇮🇷 متن‌های فارسی</h3>
                    
                    <div class="form-row">
                        <label>عنوان بالا:</label>
                        <input type="text" name="static_fa_top_title" value="<?php echo esc_attr(get_option('qrcdr_static_fa_top_title', 'کیو آر کد ساز آنلاین')); ?>" placeholder="عنوان اصلی صفحه" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>متن توضیحات بالا:</label>
                        <textarea name="static_fa_top_text" placeholder="متن توضیحات صفحه" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_fa_top_text', 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است')); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>متن تب استاتیک:</label>
                        <input type="text" name="static_fa_static_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_fa_static_tab_text', 'ساخت کیو آر کد استاتیک')); ?>" placeholder="متن تب استاتیک" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>متن تب داینامیک:</label>
                        <input type="text" name="static_fa_dynamic_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_fa_dynamic_tab_text', 'ساخت کیو آر کد دینامیک')); ?>" placeholder="متن تب داینامیک" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>مستندات پیش‌فرض:</label>
                        <textarea name="static_fa_default_doc" placeholder="مستندات پیش‌فرض" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_fa_default_doc', 'توضیحات پیش‌فرض برای دکمه‌ها')); ?></textarea>
                    </div>                    
    </form>
</div>

        <!-- Static Tab -->
        <div id="static" class="tab-content <?php echo $active_tab === 'static' ? 'active' : ''; ?>">
            <form method="POST">
                <?php wp_nonce_field('qrcdr_settings_nonce', 'qrcdr_nonce'); ?>
                <input type="hidden" name="active_tab" value="static">
                
                <h2><?php echo $current_lang === 'fa' ? 'تنظیمات دکمه‌های استاتیک' : ($current_lang === 'ar' ? 'إعدادات الأزرار الثابتة' : 'Static Button Settings'); ?></h2>
                <p><?php echo $current_lang === 'fa' ? 'در این بخش می‌توانید دکمه‌های QR استاتیک را مدیریت کنید:' : ($current_lang === 'ar' ? 'في هذا القسم يمكنك إدارة أزرار QR الثابتة:' : 'In this section you can manage static QR buttons:'); ?></p>
                
                <!-- Static Button Selector -->
                <div class="static-button-selector">
                    <h3><?php echo $current_lang === 'fa' ? 'انتخاب دکمه برای ویرایش:' : ($current_lang === 'ar' ? 'اختيار الزر للتعديل:' : 'Select button to edit:'); ?></h3>
                    <div class="button-tabs">
                        <?php 
                        // Define default texts for static buttons
                        $local_default_static_texts = [
                            'fa' => [
                                'link' => ['title' => 'لینک'],
                                'text' => ['title' => 'متن'],
                                'email' => ['title' => 'ایمیل'],
                                'location' => ['title' => 'مکان'],
                                'wifi' => ['title' => 'وای‌فای'],
                                'vcard' => ['title' => 'کارت ویزیت'],
                                'whatsapp' => ['title' => 'واتساپ'],
                                'sms' => ['title' => 'پیامک'],
                                'tel' => ['title' => 'تلفن'],
                                'skype' => ['title' => 'اسکایپ'],
                                'zoom' => ['title' => 'زوم'],
                                'event' => ['title' => 'رویداد'],
                                'paypal' => ['title' => 'پی‌پال'],
                                'bitcoin' => ['title' => 'بیت‌کوین'],
                                'ethereum' => ['title' => 'اتریوم']
                            ]
                        ];
                        
                        foreach ($static_buttons as $button): ?>
                            <button type="button" 
                                    class="static-button-tab <?php echo ($button === 'link') ? 'active' : ''; ?>" 
                                    data-button="<?php echo $button; ?>"
                                    onclick="showStaticButtonSettings('<?php echo $button; ?>')">
                                <?php 
                                $button_title = get_option("qrcdr_static_{$button}_title_fa", $local_default_static_texts['fa'][$button]['title'] ?? ucfirst($button));
                                echo esc_html($button_title);
                                ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Add New Static Button -->
                    <div style="margin-top: 20px; padding: 15px; background: #f0f8ff; border: 1px solid #b3d9ff; border-radius: 6px;">
                        <h4 style="margin-top: 0; color: #0066cc;"><?php echo $current_lang === 'fa' ? 'افزودن دکمه استاتیک جدید' : ($current_lang === 'ar' ? 'إضافة زر ثابت جديد' : 'Add New Static Button'); ?></h4>
                        <div class="form-row">
                            <label><?php echo $current_lang === 'fa' ? 'نام دکمه (انگلیسی):' : ($current_lang === 'ar' ? 'اسم الزر (بالإنجليزية):' : 'Button name (English):'); ?></label>
                            <input type="text" id="new-static-button-name" placeholder="<?php echo $current_lang === 'fa' ? 'مثال: custom_static' : ($current_lang === 'ar' ? 'مثال: custom_static' : 'Example: custom_static'); ?>" style="width: 200px; margin-right: 10px;" />
                            <button type="button" class="button button-secondary" onclick="addNewStaticButton()"><?php echo $current_lang === 'fa' ? 'افزودن دکمه جدید' : ($current_lang === 'ar' ? 'إضافة زر جديد' : 'Add New Button'); ?></button>
                        </div>
                        <small style="color: #666;"><?php echo $current_lang === 'fa' ? 'نام دکمه باید به انگلیسی و بدون فاصله باشد (مثال: custom_static)' : ($current_lang === 'ar' ? 'يجب أن يكون اسم الزر بالإنجليزية وبدون مسافات (مثال: custom_static)' : 'Button name must be in English and without spaces (Example: custom_static)'); ?></small>
                    </div>
                </div>

                <!-- Static Button Settings Panel -->
                <div id="static-button-settings-panel" class="button-settings-panel" style="display: block;">
                    <h3><?php echo $current_lang === 'fa' ? 'تنظیمات دکمه انتخاب شده' : ($current_lang === 'ar' ? 'إعدادات الزر المحدد' : 'Selected Button Settings'); ?></h3>
                    <div id="static-button-content">
                        <!-- Default content for link button -->
                        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 6px; background: #f9f9f9;">
                            <h4><?php echo $current_lang === 'fa' ? 'تنظیمات دکمه لینک' : ($current_lang === 'ar' ? 'إعدادات زر الرابط' : 'Link Button Settings'); ?></h4>
                            
                            <div class="form-row">
                                <label><?php echo $current_lang === 'fa' ? 'نمایش دکمه در فرانت:' : ($current_lang === 'ar' ? 'إظهار الزر في الواجهة الأمامية:' : 'Show Button in Frontend:'); ?></label>
                                <input type="checkbox" name="static_link_visible" value="1" checked />
                                <span style="margin-left: 10px; color: #666;"><?php echo $current_lang === 'fa' ? 'این دکمه در فرانت‌اند نمایش داده می‌شود' : ($current_lang === 'ar' ? 'سيتم عرض هذا الزر في الواجهة الأمامية' : 'This button will be displayed in frontend'); ?></span>
                            </div>
                            
                            <div class="form-row">
                                <label><?php echo $current_lang === 'fa' ? 'آیکن دکمه:' : ($current_lang === 'ar' ? 'أيقونة الزر:' : 'Button Icon:'); ?></label>
                                <input type="text" name="static_link_icon" value="<?php echo esc_attr(get_option('qrcdr_static_link_icon', 'fas fa-link')); ?>" placeholder="<?php echo $current_lang === 'fa' ? 'نام یا کد آیکن' : ($current_lang === 'ar' ? 'اسم أو رمز الأيقونة' : 'Icon name or code'); ?>" style="width: 100%;" />
                                <small style="color: #666;"><?php echo $current_lang === 'fa' ? 'مثال: fas fa-link یا URL تصویر' : ($current_lang === 'ar' ? 'مثال: fas fa-link أو رابط الصورة' : 'Example: fas fa-link or image URL'); ?></small>
                            </div>
                            
                            <div class="form-row">
                                <label><?php echo $current_lang === 'fa' ? 'رنگ دکمه:' : ($current_lang === 'ar' ? 'لون الزر:' : 'Button Color:'); ?></label>
                                <input type="color" name="static_link_color" value="<?php echo esc_attr(get_option('qrcdr_static_link_color', '#E91E63')); ?>" style="width: 60px; height: 40px;" />
                            </div>
                            
                            <div class="form-row">
                                <label><?php echo $current_lang === 'fa' ? 'عنوان دکمه (فارسی):' : ($current_lang === 'ar' ? 'عنوان الزر (عربي):' : 'Button Title (Persian):'); ?></label>
                                <input type="text" name="static_link_title_fa" value="<?php echo esc_attr(get_option('qrcdr_static_link_title_fa', 'لینک')); ?>" placeholder="<?php echo $current_lang === 'fa' ? 'عنوان دکمه' : ($current_lang === 'ar' ? 'عنوان الزر' : 'Button title'); ?>" style="width: 100%;" />
                            </div>
                            
                            <div class="form-row">
                                <label><?php echo $current_lang === 'fa' ? 'عنوان دکمه (انگلیسی):' : ($current_lang === 'ar' ? 'عنوان الزر (إنجليزي):' : 'Button Title (English):'); ?></label>
                                <input type="text" name="static_link_title_en" value="<?php echo esc_attr(get_option('qrcdr_static_link_title_en', 'Link')); ?>" placeholder="Button title" style="width: 100%;" />
                            </div>
                            
                            <div class="form-row">
                                <label><?php echo $current_lang === 'fa' ? 'توضیحات دکمه (فارسی):' : ($current_lang === 'ar' ? 'وصف الزر (عربي):' : 'Button Description (Persian):'); ?></label>
                                <textarea name="static_link_description_fa" placeholder="<?php echo $current_lang === 'fa' ? 'توضیحات کامل دکمه' : ($current_lang === 'ar' ? 'وصف كامل للزر' : 'Complete button description'); ?>" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_link_description_fa', 'ایجاد QR کد برای لینک')); ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <label><?php echo $current_lang === 'fa' ? 'توضیحات دکمه (انگلیسی):' : ($current_lang === 'ar' ? 'وصف الزر (إنجليزي):' : 'Button Description (English):'); ?></label>
                                <textarea name="static_link_description_en" placeholder="Button description" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_link_description_en', 'Create QR code for link')); ?></textarea>
                            </div>
                            
                            <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #ddd;">
                                <small style="color: #999;"><?php echo $current_lang === 'fa' ? 'برای تغییر تنظیمات سایر دکمه‌ها، روی آنها کلیک کنید.' : ($current_lang === 'ar' ? 'لتغيير إعدادات الأزرار الأخرى، انقر عليها.' : 'To change settings for other buttons, click on them.'); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <input type="submit" value="<?php echo esc_attr($admin_text['save_settings']); ?>" class="button button-primary" />
                    <button type="button" class="button button-secondary" onclick="resetStaticSettings()" style="margin-left: 10px;">
                        <?php echo $current_lang === 'fa' ? 'بازگردانی تنظیمات استاتیک' : ($current_lang === 'ar' ? 'إعادة تعيين الإعدادات الثابتة' : 'Reset Static Settings'); ?>
                    </button>
                    <button type="button" class="button button-secondary" onclick="resetAllSettings()" style="margin-left: 10px; background-color: #dc3545; border-color: #dc3545; color: white;">
                        <?php echo $current_lang === 'fa' ? 'بازگردانی کل تنظیمات' : ($current_lang === 'ar' ? 'إعادة تعيين جميع الإعدادات' : 'Reset All Settings'); ?>
                    </button>
                </div>
            </form>
        </div>
                
                <!-- English Static Texts -->
                <div id="static-lang-en" class="static-lang-content" style="display: none;">
                <h3>🇺🇸 English Static Texts</h3>
                    
                    <div class="form-row">
                        <label>Top Title:</label>
                        <input type="text" name="static_en_top_title" value="<?php echo esc_attr(get_option('qrcdr_static_en_top_title', 'Online QR Code Generator')); ?>" placeholder="Main page title" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Top Description Text:</label>
                        <textarea name="static_en_top_text" placeholder="Page description text" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_en_top_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>Static Tab Text:</label>
                        <input type="text" name="static_en_static_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_en_static_tab_text', 'Create Static QR Code')); ?>" placeholder="Static tab text" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Dynamic Tab Text:</label>
                        <input type="text" name="static_en_dynamic_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_en_dynamic_tab_text', 'Create Dynamic QR Code')); ?>" placeholder="Dynamic tab text" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Default Documentation:</label>
                        <textarea name="static_en_default_doc" placeholder="Default documentation" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_en_default_doc', 'Default description for buttons')); ?></textarea>
                    </div>
                    
                    <h4>Static Button Settings:</h4>
                    
                    <!-- Static Button List -->
                    <div class="button-list-container" style="margin-bottom: 20px;">
                        <h5>Select Button to Edit:</h5>
                        <div class="button-tabs" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
                            <?php foreach ($static_buttons as $btn): ?>
                                <button type="button" 
                                        class="static-button-tab <?php echo ($btn === 'link') ? 'active' : ''; ?>" 
                                        data-button="<?php echo $btn; ?>"
                                        onclick="showStaticButtonSettings('<?php echo $btn; ?>', event)">
                                    <?php 
                                    $button_text = $static_button_texts['en'][$btn] ?? ucfirst($btn);
                                    echo esc_html($button_text);
                                    ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Static Button Settings Panel -->
                    <div id="static-button-settings-en" class="button-settings-panel" style="display: none;">
                        <h5>Selected Button Settings:</h5>
                        <div id="static-button-content-en">
                            <!-- Content will be loaded here -->
                        </div>
                    </div>
                </div>
                <!-- Arabic Static Texts -->
<div id="static-lang-ar" class="static-lang-content" style="display: none;">
    <h3>النصوص العربية الثابتة</h3>
    
    <div class="form-row">
        <label>العنوان العلوي:</label>
        <input type="text" name="static_ar_top_title" value="<?php echo esc_attr(get_option('qrcdr_static_ar_top_title', 'منشئ رمز QR عبر الإنترنت')); ?>" placeholder="العنوان الرئيسي للصفحة" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>نص الوصف العلوي:</label>
        <textarea name="static_ar_top_text" placeholder="نص وصف الصفحة" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_ar_top_text', 'لوريم إيبسوم هو نص شكلي يستخدم في صناعة الطباعة والتنضيد')); ?></textarea>
    </div>
    
    <div class="form-row">
        <label>نص تب ثابت:</label>
        <input type="text" name="static_ar_static_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_ar_static_tab_text', 'إنشاء رمز QR ثابت')); ?>" placeholder="نص تب ثابت" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>نص تب ديناميكي:</label>
        <input type="text" name="static_ar_dynamic_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_ar_dynamic_tab_text', 'إنشاء رمز QR ديناميكي')); ?>" placeholder="نص تب ديناميكي" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>الوثائق الافتراضية:</label>
        <textarea name="static_ar_default_doc" placeholder="الوثائق الافتراضية" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_ar_default_doc', 'الوصف الافتراضي للأزرار')); ?></textarea>
    </div>
    
    <h4>إعدادات الأزرار الثابتة:</h4>
    
    <!-- Static Button List -->
    <div class="button-list-container" style="margin-bottom: 20px;">
        <h5>اختر الزر للتعديل:</h5>
        <div class="button-tabs" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
            <?php foreach ($static_buttons as $btn): ?>
                <button type="button" 
                        class="static-button-tab <?php echo ($btn === 'link') ? 'active' : ''; ?>" 
                        data-button="<?php echo $btn; ?>"
                        onclick="showStaticButtonSettings('<?php echo $btn; ?>', event)">
                    <?php 
                    $button_text = $static_button_texts['ar'][$btn] ?? ucfirst($btn);
                    echo esc_html($button_text);
                    ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Static Button Settings Panel -->
    <div id="static-button-settings-ar" class="button-settings-panel" style="display: none;">
        <h5>إعدادات الزر المحدد:</h5>
        <div id="static-button-content-ar">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Turkish Static Texts -->
<div id="static-lang-tr" class="static-lang-content" style="display: none;">
    <h3>🇹🇷 Türkçe Statik Metinler</h3>
    
    <div class="form-row">
        <label>Üst Başlık:</label>
        <input type="text" name="static_tr_top_title" value="<?php echo esc_attr(get_option('qrcdr_static_tr_top_title', 'Çevrimiçi QR Kod Oluşturucu')); ?>" placeholder="Ana sayfa başlığı" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Üst Açıklama Metni:</label>
        <textarea name="static_tr_top_text" placeholder="Sayfa açıklama metni" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_tr_top_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit')); ?></textarea>
    </div>
    
    <div class="form-row">
        <label>Statik Sekme Metni:</label>
        <input type="text" name="static_tr_static_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_tr_static_tab_text', 'Statik QR Kod Oluştur')); ?>" placeholder="Statik sekme metni" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Dinamik Sekme Metni:</label>
        <input type="text" name="static_tr_dynamic_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_tr_dynamic_tab_text', 'Dinamik QR Kod Oluştur')); ?>" placeholder="Dinamik sekme metni" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Varsayılan Dokümantasyon:</label>
        <textarea name="static_tr_default_doc" placeholder="Varsayılan dokümantasyon" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_tr_default_doc', 'Düğmeler için varsayılan açıklama')); ?></textarea>
    </div>
    
    <h4>Statik Düğme Ayarları:</h4>
    
    <!-- Static Button List -->
    <div class="button-list-container" style="margin-bottom: 20px;">
        <h5>Düzenlemek için düğme seçin:</h5>
        <div class="button-tabs" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
            <?php foreach ($static_buttons as $btn): ?>
                <button type="button" 
                        class="static-button-tab <?php echo ($btn === 'link') ? 'active' : ''; ?>" 
                        data-button="<?php echo $btn; ?>"
                        onclick="showStaticButtonSettings('<?php echo $btn; ?>', event)">
                    <?php 
                    $button_text = $static_button_texts['tr'][$btn] ?? ucfirst($btn);
                    echo esc_html($button_text);
                    ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Static Button Settings Panel -->
    <div id="static-button-settings-tr" class="button-settings-panel" style="display: none;">
        <h5>Seçilen Düğme Ayarları:</h5>
        <div id="static-button-content-tr">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Russian Static Texts -->
<div id="static-lang-ru" class="static-lang-content" style="display: none;">
    <h3>🇷🇺 Русские статические тексты</h3>
    
    <div class="form-row">
        <label>Верхний заголовок:</label>
        <input type="text" name="static_ru_top_title" value="<?php echo esc_attr(get_option('qrcdr_static_ru_top_title', 'Онлайн генератор QR-кодов')); ?>" placeholder="Главный заголовок страницы" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Верхний текст описания:</label>
        <textarea name="static_ru_top_text" placeholder="Текст описания страницы" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_ru_top_text', 'Лорем ипсум долор сит амет, консектетур адиписинг элит')); ?></textarea>
    </div>
    
    <div class="form-row">
        <label>Текст статичной вкладки:</label>
        <input type="text" name="static_ru_static_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_ru_static_tab_text', 'Создать статичный QR-код')); ?>" placeholder="Текст статичной вкладки" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Текст динамичной вкладки:</label>
        <input type="text" name="static_ru_dynamic_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_ru_dynamic_tab_text', 'Создать динамичный QR-код')); ?>" placeholder="Текст динамичной вкладки" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Документация по умолчанию:</label>
        <textarea name="static_ru_default_doc" placeholder="Документация по умолчанию" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_ru_default_doc', 'Описание по умолчанию для кнопок')); ?></textarea>
    </div>
    
    <h4>Настройки статических кнопок:</h4>
    
    <!-- Static Button List -->
    <div class="button-list-container" style="margin-bottom: 20px;">
        <h5>Выберите кнопку для редактирования:</h5>
        <div class="button-tabs" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
            <?php foreach ($static_buttons as $btn): ?>
                <button type="button" 
                        class="static-button-tab <?php echo ($btn === 'link') ? 'active' : ''; ?>" 
                        data-button="<?php echo $btn; ?>"
                        onclick="showStaticButtonSettings('<?php echo $btn; ?>', event)">
                    <?php 
                    $button_text = $static_button_texts['ru'][$btn] ?? ucfirst($btn);
                    echo esc_html($button_text);
                    ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Static Button Settings Panel -->
    <div id="static-button-settings-ru" class="button-settings-panel" style="display: none;">
        <h5>Настройки выбранной кнопки:</h5>
        <div id="static-button-content-ru">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- French Static Texts -->
<div id="static-lang-fr" class="static-lang-content" style="display: none;">
    <h3>Textes statiques français</h3>
    
    <div class="form-row">
        <label>Titre supérieur:</label>
        <input type="text" name="static_fr_top_title" value="<?php echo esc_attr(get_option('qrcdr_static_fr_top_title', 'Générateur de code QR en ligne')); ?>" placeholder="Titre principal de la page" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Texte de description supérieur:</label>
        <textarea name="static_fr_top_text" placeholder="Texte de description de la page" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_fr_top_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit')); ?></textarea>
    </div>
    
    <div class="form-row">
        <label>Texte de l\'onglet statique:</label>
        <input type="text" name="static_fr_static_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_fr_static_tab_text', 'Créer un code QR statique')); ?>" placeholder="Texte de l\'onglet statique" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Texte de l\'onglet dynamique:</label>
        <input type="text" name="static_fr_dynamic_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_fr_dynamic_tab_text', 'Créer un code QR dynamique')); ?>" placeholder="Texte de l\'onglet dynamique" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Documentation par défaut:</label>
        <textarea name="static_fr_default_doc" placeholder="Documentation par défaut" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_fr_default_doc', 'Description par défaut pour les boutons')); ?></textarea>
    </div>
    
    <h4>Paramètres des boutons statiques:</h4>
    
    <!-- Static Button List -->
    <div class="button-list-container" style="margin-bottom: 20px;">
        <h5>Sélectionnez le bouton à modifier:</h5>
        <div class="button-tabs" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
            <?php foreach ($static_buttons as $btn): ?>
                <button type="button" 
                        class="static-button-tab <?php echo ($btn === 'link') ? 'active' : ''; ?>" 
                        data-button="<?php echo $btn; ?>"
                        onclick="showStaticButtonSettings('<?php echo $btn; ?>', event)">
                    <?php 
                    $button_text = $static_button_texts['fr'][$btn] ?? ucfirst($btn);
                    echo esc_html($button_text);
                    ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Static Button Settings Panel -->
    <div id="static-button-settings-fr" class="button-settings-panel" style="display: none;">
        <h5>Paramètres du bouton sélectionné:</h5>
        <div id="static-button-content-fr">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- German Static Texts -->
<div id="static-lang-de" class="static-lang-content" style="display: none;">
    <h3>Deutsche statische Texte</h3>
    
    <div class="form-row">
        <label>Oberer Titel:</label>
        <input type="text" name="static_de_top_title" value="<?php echo esc_attr(get_option('qrcdr_static_de_top_title', 'Online QR-Code Generator')); ?>" placeholder="Hauptseitentitel" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Oberer Beschreibungstext:</label>
        <textarea name="static_de_top_text" placeholder="Seitenbeschreibungstext" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('qrcdr_static_de_top_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit')); ?></textarea>
    </div>
    
    <div class="form-row">
        <label>Statischer Tab-Text:</label>
        <input type="text" name="static_de_static_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_de_static_tab_text', 'Statischen QR-Code erstellen')); ?>" placeholder="Statischer Tab-Text" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Dynamischer Tab-Text:</label>
        <input type="text" name="static_de_dynamic_tab_text" value="<?php echo esc_attr(get_option('qrcdr_static_de_dynamic_tab_text', 'Dynamischen QR-Code erstellen')); ?>" placeholder="Dynamischer Tab-Text" style="width: 100%;" />
    </div>
    
    <div class="form-row">
        <label>Standarddokumentation:</label>
        <textarea name="static_de_default_doc" placeholder="Standarddokumentation" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option('static_de_default_doc', 'Standardbeschreibung für Schaltflächen')); ?></textarea>
    </div>
    
    <h4>Statische Schaltflächeneinstellungen:</h4>
    
    <!-- Static Button List -->
    <div class="button-list-container" style="margin-bottom: 20px;">
        <h5>Wählen Sie die zu bearbeitende Schaltfläche:</h5>
        <div class="button-tabs" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
            <?php foreach ($static_buttons as $btn): ?>
                <button type="button" 
                        class="static-button-tab <?php echo ($btn === 'link') ? 'active' : ''; ?>" 
                        data-button="<?php echo $btn; ?>"
                        onclick="showStaticButtonSettings('<?php echo $btn; ?>', event)">
                    <?php 
                    $button_text = $static_button_texts['de'][$btn] ?? ucfirst($btn);
                    echo esc_html($button_text);
                    ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Static Button Settings Panel -->
    <div id="static-button-settings-de" class="button-settings-panel" style="display: none;">
        <h5>Einstellungen der ausgewählten Schaltfläche:</h5>
        <div id="static-button-content-de">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
                
                <input type="submit" value="<?php echo esc_attr($admin_text['save_settings']); ?>" class="button button-primary" />
            </form>
        </div>

        <!-- Dynamic Tab -->
        <div id="dynamic" class="tab-content <?php echo $active_tab === 'dynamic' ? 'active' : ''; ?>">
            <form method="POST">
                <?php wp_nonce_field('qrcdr_settings_nonce', 'qrcdr_nonce'); ?>
                <input type="hidden" name="active_tab" value="dynamic">
                
                <h2><?php echo $current_lang === 'fa' ? 'تنظیمات دکمه‌های دینامیک' : ($current_lang === 'ar' ? 'إعدادات الأزرار الديناميكية' : 'Dynamic Button Settings'); ?></h2>
                <p><?php echo $current_lang === 'fa' ? 'در این بخش می‌توانید دکمه‌های QR دینامیک را مدیریت کنید:' : ($current_lang === 'ar' ? 'في هذا القسم يمكنك إدارة أزرار QR الديناميكية:' : 'In this section you can manage dynamic QR buttons:'); ?></p>
                
                <!-- Dynamic Button Selector -->
                <div class="dynamic-button-selector">
                    <h3><?php echo $current_lang === 'fa' ? 'انتخاب دکمه برای ویرایش:' : ($current_lang === 'ar' ? 'اختيار الزر للتعديل:' : 'Select button to edit:'); ?></h3>
                    <div class="button-tabs">
                        <?php 
                        // Define default texts for dynamic buttons first
                        $local_default_dynamic_texts = [
                            'fa' => [
                                'business_card' => ['title' => 'کارت ویزیت'],
                                'social_media' => ['title' => 'شبکه‌های اجتماعی'],
                                'photo_gallery' => ['title' => 'گالری عکس'],
                                'video_gallery' => ['title' => 'گالری ویدیو'],
                                'link' => ['title' => 'لینک'],
                                'website' => ['title' => 'وب‌سایت'],
                                'online_menu' => ['title' => 'منوی آنلاین'],
                                'insurance' => ['title' => 'بیمه'],
                                'online_shop' => ['title' => 'فروشگاه آنلاین'],
                                'booking' => ['title' => 'رزرو'],
                                'commercial_complex' => ['title' => 'مجتمع تجاری'],
                                'product' => ['title' => 'محصول'],
                                'product_label' => ['title' => 'برچسب محصول'],
                                'gold_label' => ['title' => 'برچسب طلا'],
                                'catalog' => ['title' => 'کاتالوگ']
                            ]
                        ];
                        
                        foreach ($dynamic_buttons as $button): ?>
                            <button type="button" 
                                    class="dynamic-button-tab <?php echo ($button === 'business_card') ? 'active' : ''; ?>" 
                                    data-button="<?php echo $button; ?>">
                                <?php 
                                $button_title = get_option("qrcdr_dynamic_{$button}_title_fa", $local_default_dynamic_texts['fa'][$button]['title'] ?? ucfirst($button));
                                echo esc_html($button_title);
                                ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Add New Dynamic Button -->
                    <div style="margin-top: 20px; padding: 15px; background: #f0f8ff; border: 1px solid #b3d9ff; border-radius: 6px;">
                        <h4 style="margin-top: 0; color: #0066cc;"><?php echo $current_lang === 'fa' ? 'افزودن دکمه دینامیک جدید' : ($current_lang === 'ar' ? 'إضافة زر ديناميكي جديد' : 'Add New Dynamic Button'); ?></h4>
                        <div class="form-row">
                            <label><?php echo $current_lang === 'fa' ? 'نام دکمه (انگلیسی):' : ($current_lang === 'ar' ? 'اسم الزر (بالإنجليزية):' : 'Button name (English):'); ?></label>
                            <input type="text" id="new-button-name" placeholder="<?php echo $current_lang === 'fa' ? 'مثال: custom_button' : ($current_lang === 'ar' ? 'مثال: custom_button' : 'Example: custom_button'); ?>" style="width: 200px; margin-right: 10px;" />
                            <button type="button" class="button button-secondary" onclick="addNewDynamicButton()"><?php echo $current_lang === 'fa' ? 'افزودن دکمه جدید' : ($current_lang === 'ar' ? 'إضافة زر جديد' : 'Add New Button'); ?></button>
                        </div>
                        <small style="color: #666;"><?php echo $current_lang === 'fa' ? 'نام دکمه باید به انگلیسی و بدون فاصله باشد (مثال: custom_button)' : ($current_lang === 'ar' ? 'يجب أن يكون اسم الزر بالإنجليزية وبدون مسافات (مثال: custom_button)' : 'Button name must be in English and without spaces (Example: custom_button)'); ?></small>
                    </div>
                </div>

                <?php 
                // Define dynamic buttons
                $dynamic_buttons = ['business_card', 'social_media', 'photo_gallery', 'video_gallery', 'link', 'website', 'online_menu', 'insurance', 'online_shop', 'booking', 'commercial_complex', 'product', 'product_label', 'gold_label', 'catalog'];
                
                // Define default texts for dynamic buttons
                $default_dynamic_texts = [
                    'fa' => [
                        'business_card' => ['title' => 'کارت ویزیت', 'description' => 'ایجاد QR کد برای کارت ویزیت'],
                        'social_media' => ['title' => 'شبکه‌های اجتماعی', 'description' => 'ایجاد QR کد برای شبکه‌های اجتماعی'],
                        'photo_gallery' => ['title' => 'گالری عکس', 'description' => 'ایجاد QR کد برای گالری عکس'],
                        'video_gallery' => ['title' => 'گالری ویدیو', 'description' => 'ایجاد QR کد برای گالری ویدیو'],
                        'link' => ['title' => 'لینک', 'description' => 'ایجاد QR کد برای لینک'],
                        'website' => ['title' => 'وب‌سایت', 'description' => 'ایجاد QR کد برای وب‌سایت'],
                        'online_menu' => ['title' => 'منوی آنلاین', 'description' => 'ایجاد QR کد برای منوی آنلاین'],
                        'insurance' => ['title' => 'بیمه', 'description' => 'ایجاد QR کد برای بیمه'],
                        'online_shop' => ['title' => 'فروشگاه آنلاین', 'description' => 'ایجاد QR کد برای فروشگاه آنلاین'],
                        'booking' => ['title' => 'رزرو', 'description' => 'ایجاد QR کد برای رزرو'],
                        'commercial_complex' => ['title' => 'مجتمع تجاری', 'description' => 'ایجاد QR کد برای مجتمع تجاری'],
                        'product' => ['title' => 'محصول', 'description' => 'ایجاد QR کد برای محصول'],
                        'product_label' => ['title' => 'برچسب محصول', 'description' => 'ایجاد QR کد برای برچسب محصول'],
                        'gold_label' => ['title' => 'برچسب طلا', 'description' => 'ایجاد QR کد برای برچسب طلا'],
                        'catalog' => ['title' => 'کاتالوگ', 'description' => 'ایجاد QR کد برای کاتالوگ']
                    ],
                    'en' => [
                        'business_card' => ['title' => 'Business Card', 'description' => 'Create QR code for business card'],
                        'social_media' => ['title' => 'Social Media', 'description' => 'Create QR code for social media'],
                        'photo_gallery' => ['title' => 'Photo Gallery', 'description' => 'Create QR code for photo gallery'],
                        'video_gallery' => ['title' => 'Video Gallery', 'description' => 'Create QR code for video gallery'],
                        'link' => ['title' => 'Link', 'description' => 'Create QR code for link'],
                        'website' => ['title' => 'Website', 'description' => 'Create QR code for website'],
                        'online_menu' => ['title' => 'Online Menu', 'description' => 'Create QR code for online menu'],
                        'insurance' => ['title' => 'Insurance', 'description' => 'Create QR code for insurance'],
                        'online_shop' => ['title' => 'Online Shop', 'description' => 'Create QR code for online shop'],
                        'booking' => ['title' => 'Booking', 'description' => 'Create QR code for booking'],
                        'commercial_complex' => ['title' => 'Commercial Complex', 'description' => 'Create QR code for commercial complex'],
                        'product' => ['title' => 'Product', 'description' => 'Create QR code for product'],
                        'product_label' => ['title' => 'Product Label', 'description' => 'Create QR code for product label'],
                        'gold_label' => ['title' => 'Gold Label', 'description' => 'Create QR code for gold label'],
                        'catalog' => ['title' => 'Catalog', 'description' => 'Create QR code for catalog']
                    ],
                    'ar' => [
                        'business_card' => ['title' => 'بطاقة عمل', 'description' => 'إنشاء رمز QR لبطاقة العمل'],
                        'social_media' => ['title' => 'وسائل التواصل الاجتماعي', 'description' => 'إنشاء رمز QR لوسائل التواصل الاجتماعي'],
                        'photo_gallery' => ['title' => 'معرض الصور', 'description' => 'إنشاء رمز QR لمعرض الصور'],
                        'video_gallery' => ['title' => 'معرض الفيديو', 'description' => 'إنشاء رمز QR لمعرض الفيديو'],
                        'link' => ['title' => 'رابط', 'description' => 'إنشاء رمز QR للرابط'],
                        'website' => ['title' => 'موقع ويب', 'description' => 'إنشاء رمز QR للموقع'],
                        'online_menu' => ['title' => 'قائمة طعام على الإنترنت', 'description' => 'إنشاء رمز QR للقائمة'],
                        'insurance' => ['title' => 'تأمين', 'description' => 'إنشاء رمز QR للتأمين'],
                        'online_shop' => ['title' => 'متجر على الإنترنت', 'description' => 'إنشاء رمز QR للمتجر'],
                        'booking' => ['title' => 'حجز', 'description' => 'إنشاء رمز QR للحجز'],
                        'commercial_complex' => ['title' => 'مجمع تجاري', 'description' => 'إنشاء رمز QR للمجمع'],
                        'product' => ['title' => 'منتج', 'description' => 'إنشاء رمز QR للمنتج'],
                        'product_label' => ['title' => 'ملصق المنتج', 'description' => 'إنشاء رمز QR للملصق'],
                        'gold_label' => ['title' => 'ملصق ذهبي', 'description' => 'إنشاء رمز QR للملصق الذهبي'],
                        'catalog' => ['title' => 'كتالوج', 'description' => 'إنشاء رمز QR للكتالوج']
                    ],
                    'tr' => [
                        'business_card' => ['title' => 'İş Kartı', 'description' => 'İş kartı için QR kod oluştur'],
                        'social_media' => ['title' => 'Sosyal Medya', 'description' => 'Sosyal medya için QR kod oluştur'],
                        'photo_gallery' => ['title' => 'Fotoğraf Galerisi', 'description' => 'Fotoğraf galerisi için QR kod oluştur'],
                        'video_gallery' => ['title' => 'Video Galerisi', 'description' => 'Video galerisi için QR kod oluştur'],
                        'link' => ['title' => 'Bağlantı', 'description' => 'Bağlantı için QR kod oluştur'],
                        'website' => ['title' => 'Web Sitesi', 'description' => 'Web sitesi için QR kod oluştur'],
                        'online_menu' => ['title' => 'Çevrimiçi Menü', 'description' => 'Çevrimiçi menü için QR kod oluştur'],
                        'insurance' => ['title' => 'Sigorta', 'description' => 'Sigorta için QR kod oluştur'],
                        'online_shop' => ['title' => 'Çevrimiçi Mağaza', 'description' => 'Çevrimiçi mağaza için QR kod oluştur'],
                        'booking' => ['title' => 'Rezervasyon', 'description' => 'Rezervasyon için QR kod oluştur'],
                        'commercial_complex' => ['title' => 'Ticari Kompleks', 'description' => 'Ticari kompleks için QR kod oluştur'],
                        'product' => ['title' => 'Ürün', 'description' => 'Ürün için QR kod oluştur'],
                        'product_label' => ['title' => 'Ürün Etiketi', 'description' => 'Ürün etiketi için QR kod oluştur'],
                        'gold_label' => ['title' => 'Altın Etiket', 'description' => 'Altın etiket için QR kod oluştur'],
                        'catalog' => ['title' => 'Katalog', 'description' => 'Katalog için QR kod oluştur']
                    ],
                    'ru' => [
                        'business_card' => ['title' => 'Визитная карточка', 'description' => 'Создать QR-код для визитной карточки'],
                        'social_media' => ['title' => 'Социальные сети', 'description' => 'Создать QR-код для социальных сетей'],
                        'photo_gallery' => ['title' => 'Фотогалерея', 'description' => 'Создать QR-код для фотогалереи'],
                        'video_gallery' => ['title' => 'Видеогалерея', 'description' => 'Создать QR-код для видеогалереи'],
                        'link' => ['title' => 'Ссылка', 'description' => 'Создать QR-код для ссылки'],
                        'website' => ['title' => 'Веб-сайт', 'description' => 'Создать QR-код для веб-сайта'],
                        'online_menu' => ['title' => 'Онлайн меню', 'description' => 'Создать QR-код для онлайн меню'],
                        'insurance' => ['title' => 'Страхование', 'description' => 'Создать QR-код для страхования'],
                        'online_shop' => ['title' => 'Онлайн магазин', 'description' => 'Создать QR-код для онлайн магазина'],
                        'booking' => ['title' => 'Бронирование', 'description' => 'Создать QR-код для бронирования'],
                        'commercial_complex' => ['title' => 'Торговый комплекс', 'description' => 'Создать QR-кوд для торгового комплекса'],
                        'product' => ['title' => 'Продукт', 'description' => 'Создать QR-код для продукта'],
                        'product_label' => ['title' => 'Этикетка продукта', 'description' => 'Создать QR-код для этикетки продукта'],
                        'gold_label' => ['title' => 'Золотая этикетка', 'description' => 'Создать QR-код для золотой этикетки'],
                        'catalog' => ['title' => 'Каталог', 'description' => 'Создать QR-код для каталога']
                    ],
                    'fr' => [
                        'business_card' => ['title' => 'Carte de visite', 'description' => 'Créer un code QR pour la carte de visite'],
                        'social_media' => ['title' => 'Réseaux sociaux', 'description' => 'Créer un code QR pour les réseaux sociaux'],
                        'photo_gallery' => ['title' => 'Galerie photo', 'description' => 'Créer un code QR pour la galerie photo'],
                        'video_gallery' => ['title' => 'Galerie vidéo', 'description' => 'Créer un code QR pour la galerie vidéo'],
                        'link' => ['title' => 'Lien', 'description' => 'Créer un code QR pour le lien'],
                        'website' => ['title' => 'Site web', 'description' => 'Créer un code QR pour le site web'],
                        'online_menu' => ['title' => 'Menu en ligne', 'description' => 'Créer un code QR pour le menu en ligne'],
                        'insurance' => ['title' => 'Assurance', 'description' => 'Créer un code QR pour l\'assurance'],
                        'online_shop' => ['title' => 'Boutique en ligne', 'description' => 'Créer un code QR pour la boutique en ligne'],
                        'booking' => ['title' => 'Réservation', 'description' => 'Créer un code QR pour la réservation'],
                        'commercial_complex' => ['title' => 'Complexe commercial', 'description' => 'Créer un code QR pour le complexe commercial'],
                        'product' => ['title' => 'Produit', 'description' => 'Créer un code QR pour le produit'],
                        'product_label' => ['title' => 'Étiquette produit', 'description' => 'Créer un code QR pour l\'étiquette produit'],
                        'gold_label' => ['title' => 'Étiquette dorée', 'description' => 'Créer un code QR pour l\'étiquette dorée'],
                        'catalog' => ['title' => 'Catalogue', 'description' => 'Créer un code QR pour le catalogue']
                    ],
                    'de' => [
                        'business_card' => ['title' => 'Visitenkarte', 'description' => 'QR-Code für Visitenkarte erstellen'],
                        'social_media' => ['title' => 'Soziale Medien', 'description' => 'QR-Code für soziale Medien erstellen'],
                        'photo_gallery' => ['title' => 'Fotogalerie', 'description' => 'QR-Code für Fotogalerie erstellen'],
                        'video_gallery' => ['title' => 'Videogalerie', 'description' => 'QR-Code für Videogalerie erstellen'],
                        'link' => ['title' => 'Link', 'description' => 'QR-Code für Link erstellen'],
                        'website' => ['title' => 'Website', 'description' => 'QR-Code für Website erstellen'],
                        'online_menu' => ['title' => 'Online-Menü', 'description' => 'QR-Code für Online-Menü erstellen'],
                        'insurance' => ['title' => 'Versicherung', 'description' => 'QR-Code für Versicherung erstellen'],
                        'online_shop' => ['title' => 'Online-Shop', 'description' => 'QR-Code für Online-Shop erstellen'],
                        'booking' => ['title' => 'Buchung', 'description' => 'QR-Code für Buchung erstellen'],
                        'commercial_complex' => ['title' => 'Gewerbekomplex', 'description' => 'QR-Code für Gewerbekomplex erstellen'],
                        'product' => ['title' => 'Produkt', 'description' => 'QR-Code für Produkt erstellen'],
                        'product_label' => ['title' => 'Produktetikett', 'description' => 'QR-Code für Produktetikett erstellen'],
                        'gold_label' => ['title' => 'Gold-Etikett', 'description' => 'QR-Code für Gold-Etikett erstellen'],
                        'catalog' => ['title' => 'Katalog', 'description' => 'QR-Code für Katalog erstellen']
                    ]
                ];
                ?>
                
                <!-- Dynamic Button Settings Container -->
                <?php foreach ($dynamic_buttons as $button): ?>
                    <div id="dynamic-settings-<?php echo $button; ?>" class="dynamic-button-settings <?php echo ($button === 'business_card') ? 'active' : ''; ?>" style="border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 6px; background: #f9f9f9; <?php echo ($button === 'business_card') ? '' : 'display: none;'; ?>">
                        <h3><?php echo esc_html($default_dynamic_texts['fa'][$button]['title']); ?></h3>
                        
                        <!-- Visibility Toggle -->
                        <div class="form-row">
                            <label>نمایش دکمه:</label>
                            <input type="checkbox" name="dynamic_<?php echo $button; ?>_visible" value="1" <?php checked(get_option("qrcdr_dynamic_{$button}_visible", '1'), '1'); ?> />
                            <span style="margin-left: 10px; color: #666;">این دکمه در فرانت‌اند نمایش داده می‌شود</span>
                        </div>
    
                        <!-- Language Tabs -->
                        <div class="language-tabs" data-button="<?php echo $button; ?>">
                            <h4><?php echo $current_lang === 'fa' ? 'متن‌های چندزبانه' : 'Multi-language Texts'; ?></h4>
                            <div style="margin-bottom: 15px;">
                                <button type="button" class="lang-tab-btn active" onclick="showDynamicLanguageTab('<?php echo $button; ?>', 'fa', event)">فارسی</button>
                                <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('<?php echo $button; ?>', 'en', event)">English</button>
                                <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('<?php echo $button; ?>', 'ar', event)">العربية</button>
                                <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('<?php echo $button; ?>', 'tr', event)">Türkçe</button>
                                <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('<?php echo $button; ?>', 'ru', event)">Русский</button>
                                <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('<?php echo $button; ?>', 'fr', event)">Français</button>
                                <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('<?php echo $button; ?>', 'de', event)">Deutsch</button>
                               
                            </div>
                        </div>
                        <!-- Language Content for each language -->
                        <?php foreach (['fa', 'en', 'ar', 'tr', 'ru', 'fr', 'de'] as $lang): ?>
                            <div id="dynamic-<?php echo $button; ?>-<?php echo $lang; ?>" class="dynamic-lang-content <?php echo ($lang === 'fa') ? 'active' : ''; ?>" style="<?php echo ($lang === 'fa') ? '' : 'display: none;'; ?>">
                                <h5><?php echo $default_dynamic_texts[$lang][$button]['title']; ?></h5>
                    
                    <div class="form-row">
                                    <label><?php echo $lang === 'fa' ? 'عنوان دکمه:' : ($lang === 'ar' ? 'عنوان الزر:' : 'Button Title:'); ?></label>
                                    <input type="text" name="dynamic_<?php echo $button; ?>_title_<?php echo $lang; ?>" value="<?php echo esc_attr(get_option("qrcdr_dynamic_{$button}_title_{$lang}", $default_dynamic_texts[$lang][$button]['title'])); ?>" placeholder="<?php echo $lang === 'fa' ? 'عنوان دکمه' : ($lang === 'ar' ? 'عنوان الزر' : 'Button Title'); ?>" style="width: 100%;" />
                            </div>
                            
                                <div class="form-row">
                                    <label><?php echo $lang === 'fa' ? 'توضیحات دکمه:' : ($lang === 'ar' ? 'وصف الزر:' : 'Button Description:'); ?></label>
                                    <textarea name="dynamic_<?php echo $button; ?>_description_<?php echo $lang; ?>" placeholder="<?php echo $lang === 'fa' ? 'توضیحات کامل دکمه' : ($lang === 'ar' ? 'وصف كامل للزر' : 'Complete button description'); ?>" style="width: 100%; height: 60px;"><?php echo esc_textarea(get_option("qrcdr_dynamic_{$button}_description_{$lang}", $default_dynamic_texts[$lang][$button]['description'])); ?></textarea>
                                </div>

                    <div class="form-row">
                            <label><?php echo $lang === 'fa' ? 'زیر متن دکمه:' : ($lang === 'ar' ? 'النص الفرعي للزر:' : 'Button Subtext:'); ?></label>
                            <input type="text" name="dynamic_<?php echo $button; ?>_subtext_<?php echo $lang; ?>" value="<?php echo esc_attr(get_option("qrcdr_dynamic_{$button}_subtext_{$lang}", '')); ?>" placeholder="<?php echo $lang === 'fa' ? 'زیر متن دکمه' : ($lang === 'ar' ? 'النص الفرعي للزر' : 'Button subtext'); ?>" style="width: 100%;" />
                    </div>
                    </div>
                    <?php endforeach; ?>


                        <!-- Additional Fields -->
                        <div class="form-row">
                        <label><?php echo $current_lang === 'fa' ? 'آیکن دکمه (Font Awesome):' : ($current_lang === 'ar' ? 'أيقونة الزر (Font Awesome):' : 'Button Icon (Font Awesome):'); ?></label>                            <div class="media-upload-field">
                                <input type="text" name="dynamic_<?php echo $button; ?>_icon_image" value="<?php echo esc_attr(get_option("qrcdr_dynamic_{$button}_icon_image", '')); ?>" placeholder="مثال: fas fa-gem" style="flex: 1;" />
                                <button type="button" class="media-upload-button" onclick="selectIconImage('<?php echo $button; ?>')">انتخاب آیکن</button>
                                <div class="media-upload-preview">
                                    <?php 
                                    $icon_class = get_option("qrcdr_dynamic_{$button}_icon_image", '');
                                    if ($icon_class): ?>
                                        <i class="<?php echo esc_attr($icon_class); ?>" style="font-size: 24px; color: #0073aa;"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                        <label><?php echo $current_lang === 'fa' ? 'عکس نمونه دکمه:' : ($current_lang === 'ar' ? 'صورة زر العينة:' : 'Button Sample Image:'); ?></label>                            <div class="media-upload-field">
                                <input type="text" name="dynamic_<?php echo $button; ?>_sample_image" value="<?php echo esc_attr(get_option("qrcdr_dynamic_{$button}_sample_image", '')); ?>" placeholder="URL عکس نمونه یا انتخاب از گالری" style="flex: 1;" />
                                <button type="button" class="media-upload-button" onclick="selectSampleImage('<?php echo $button; ?>')">انتخاب عکس</button>
                                <div class="media-upload-preview">
                                    <?php 
                                    $sample_image_url = get_option("qrcdr_dynamic_{$button}_sample_image", '');
                                    if ($sample_image_url): ?>
                                        <img src="<?php echo esc_url($sample_image_url); ?>" alt="عکس نمونه" style="max-width: 60px; max-height: 40px;" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                        <label><?php echo $current_lang === 'fa' ? 'رنگ دکمه:' : ($current_lang === 'ar' ? 'لون الزر:' : 'Button Color:'); ?></label>                            
                        <div class="color-picker-field">
                                <input type="color" name="dynamic_<?php echo $button; ?>_button_color" value="<?php echo esc_attr(get_option("qrcdr_dynamic_{$button}_button_color", '#E91E63')); ?>" class="color-picker-input" />
                            </div>
                        </div>
                        
                        <div class="form-row">
                        <label><?php echo $current_lang === 'fa' ? 'لینک دکمه:' : ($current_lang === 'ar' ? 'رابط الزر:' : 'Button Link:'); ?></label>                            <input type="url" name="dynamic_<?php echo $button; ?>_link" value="<?php echo esc_attr(get_option("qrcdr_dynamic_{$button}_link", '')); ?>" placeholder="https://example.com" style="width: 100%;" />
                        </div>
                        
                        <div class="form-row">
                        <label><?php echo $current_lang === 'fa' ? 'متن دکمه ساخت:' : ($current_lang === 'ar' ? 'نص زر الإنشاء:' : 'Create Button Text:'); ?></label>                            <input type="text" name="dynamic_<?php echo $button; ?>_create_text" value="<?php echo esc_attr(get_option("qrcdr_dynamic_{$button}_create_text", 'ساخت کیوآر')); ?>" placeholder="متن دکمه ساخت" style="width: 100%;" />
                        </div>
                        
                        <div class="form-row">
                        <label><?php echo $current_lang === 'fa' ? 'متن دکمه بازدید:' : ($current_lang === 'ar' ? 'نص زر الزيارة:' : 'Visit Button Text:'); ?></label>                            <input type="text" name="dynamic_<?php echo $button; ?>_visit_text" value="<?php echo esc_attr(get_option("qrcdr_dynamic_{$button}_visit_text", 'بازدید از سایت')); ?>" placeholder="متن دکمه بازدید" style="width: 100%;" />
                        </div>
                            <!-- Delete Button -->
                            <div class="form-row" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                                            <button type="button" class="button button-secondary delete" onclick="deleteDynamicButton('<?php echo $button; ?>')">
                    <?php echo $current_lang === 'fa' ? 'حذف دکمه' : ($current_lang === 'ar' ? 'حذف الزر' : 'Delete Button'); ?> "<?php echo esc_html($default_dynamic_texts['fa'][$button]['title']); ?>"
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <input type="submit" value="<?php echo esc_attr($admin_text['save_settings']); ?>" class="button button-primary" />
                    <button type="button" class="button button-secondary" onclick="resetDynamicSettings()" style="margin-left: 10px;">
                        <?php echo $current_lang === 'fa' ? 'بازگردانی تنظیمات دینامیک' : ($current_lang === 'ar' ? 'إعادة تعيين الإعدادات الديناميكية' : 'Reset Dynamic Settings'); ?>
                    </button>
                    <button type="button" class="button button-secondary" onclick="resetAllSettings()" style="margin-left: 10px; background-color: #dc3545; border-color: #dc3545; color: white;">
                        <?php echo $current_lang === 'fa' ? 'بازگردانی کل تنظیمات' : ($current_lang === 'ar' ? 'إعادة تعيين جميع الإعدادات' : 'Reset All Settings'); ?>
                    </button>
                </div>
            </form>
        </div>

        
        <!-- Icon Selection Popup -->
        <div id="icon-popup" class="icon-popup-overlay" style="display: none;">
            <div class="icon-popup-content">
                <div class="icon-popup-header">
                <h3><?php echo $lang === 'fa' ? 'انتخاب آیکن' : ($lang === 'ar' ? 'اختيار الأيقونة' : 'Icon Selection'); ?></h3>                    <button type="button" class="icon-popup-close" onclick="closeIconPopup()">&times;</button>
                </div>
                <div class="icon-popup-body">
                    <div class="icon-search">
                        <input type="text" id="icon-search-input" placeholder="جستجو در آیکن‌ها..." />
                    </div>
                    <div class="icon-grid" id="icon-grid">
                        <!-- آیکن‌ها اینجا لود می‌شوند -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Tab -->
        <div id="messages" class="tab-content <?php echo $active_tab === 'messages' ? 'active' : ''; ?>">
            <form method="POST">
                <?php wp_nonce_field('qrcdr_settings_nonce', 'qrcdr_nonce'); ?>
                <input type="hidden" name="active_tab" value="messages">
                <h2><?php echo $current_lang === 'fa' ? 'مدیریت متن‌های چندزبانه' : ($current_lang === 'ar' ? 'إدارة النصوص متعددة اللغات' : 'Multi-language Text Management'); ?></h2>
                <p><?php echo $current_lang === 'fa' ? 'در این بخش می‌توانید متن‌های نمایش داده شده در پلاگین را برای زبان‌های مختلف مدیریت کنید:' : ($current_lang === 'ar' ? 'في هذا القسم يمكنك إدارة النصوص المعروضة في الإضافة للغات المختلفة:' : 'In this section you can manage the texts displayed in the plugin for different languages:'); ?></p>
                
                <div class="language-tabs">
                    <h3><?php echo $current_lang === 'fa' ? 'تب زبان‌ها' : ($current_lang === 'ar' ? 'تبويبات اللغات' : 'Language Tabs'); ?></h3>
                    <div style="margin-bottom: 20px;">
                                <button type="button" class="lang-tab-btn active" onclick="showLanguageTab('fa', event)">فارسی</button>
                                <button type="button" class="lang-tab-btn" onclick="showLanguageTab('en', event)">English</button>
                                <button type="button" class="lang-tab-btn" onclick="showLanguageTab('ar', event)">العربية</button>
                                <button type="button" class="lang-tab-btn" onclick="showLanguageTab('tr', event)">Türkçe</button>
                                <button type="button" class="lang-tab-btn" onclick="showLanguageTab('ru', event)">Русский</button>
                                <button type="button" class="lang-tab-btn" onclick="showLanguageTab('fr', event)">Français</button>
                                <button type="button" class="lang-tab-btn" onclick="showLanguageTab('de', event)">Deutsch</button>
                    </div>
                </div>
                
                <!-- Persian Messages -->
                <div id="lang-fa" class="lang-content active">
                    <h3>🇮🇷 متن‌های فارسی</h3>
                    
                    <div class="form-row">
                        <label>عنوان اصلی:</label>
                        <input type="text" name="messages_fa_title" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_title', $default_messages['fa']['title'])); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>عنوان بالای صفحه:</label>
                        <input type="text" name="messages_fa_topTitle" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_topTitle', $default_messages['fa']['topTitle'])); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>متن بالای صفحه:</label>
                        <textarea name="messages_fa_topText" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('qrcdr_messages_fa_topText', $default_messages['fa']['topText'])); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>متن تب استاتیک:</label>
                        <input type="text" name="messages_fa_staticTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_staticTabText', $default_messages['fa']['staticTabText'])); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>متن تب دینامیک:</label>
                        <input type="text" name="messages_fa_dynamicTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_dynamicTabText', $default_messages['fa']['dynamicTabText'])); ?>" style="width: 100%;" />
                    </div>
                    
                    <h4>متن‌های واسط کاربری:</h4>
                    <div class="form-row">
                        <label>تولید کد QR:</label>
                        <input type="text" name="messages_fa_generate_qrcode" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_generate_qrcode', 'تولید کد QR')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>سطح تصحیح خطا:</label>
                        <input type="text" name="messages_fa_error_correction_level" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_error_correction_level', 'دقت')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>انتخاب قاب:</label>
                        <input type="text" name="messages_fa_frame_selection" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_frame_selection', 'انتخاب قاب')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>انتخاب رنگ:</label>
                        <input type="text" name="messages_fa_color_selection" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_color_selection', 'انتخاب رنگ')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>بازدید از وب‌سایت:</label>
                        <input type="text" name="messages_fa_visit_website" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_visit_website', 'بازدید از وب‌سایت')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>اطلاعات بیشتر:</label>
                        <input type="text" name="messages_fa_more_info" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_more_info', 'اطلاعات بیشتر')); ?>" style="width: 100%;" />
                    </div>

                    <h2>متن‌های دکمه‌های استاتیک:</h2>
                    <div class="qrcdr-accordion">
                    <?php foreach ($static_buttons as $btn): 
                        $label = $static_button_texts['fa'][$btn] ?? ucfirst($btn);
                    ?>
                        <div class="qrcdr-acc-item">
                            <button type="button" class="qrcdr-acc-header" onclick="qrcdrToggleAccordion(this)">
                                <?php
                                    // فرض بر این است که متغیر $static_button_icons حاوی آیکن هر دکمه باشد (مثلاً کلاس FontAwesome یا SVG)
                                    // اگر چنین آرایه‌ای ندارید، باید آن را تعریف کنید یا مقداردهی کنید.
                                    $icon = $static_button_icons[$btn] ?? '';
                                ?>
                                <?php if ($icon): ?>
                                    <span class="qrcdr-btn-icon" style="margin-left:8px;"><?php echo $icon; ?></span>
                                <?php endif; ?>
                                <span><?php echo esc_html($label); ?></span>
                                <span class="qrcdr-acc-arrow">▾</span>
                            </button>
                            <div class="qrcdr-acc-body" style="display: none;">
                                <div class="form-row">
                                    <label>متن دکمه:</label>
                                    <input type="text" name="messages_fa_buttons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_buttons', [])[$btn] ?? "کیو آر کد {$btn}"); ?>" style="width: 100%;" />
                                </div>
                                <div class="form-row">
                                    <label>متن راهنما:</label>
                                    <input type="text" name="messages_fa_buttons_text[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_buttons_text', [])[$btn] ?? "{$btn} کیو آر کد را وارد کنید"); ?>" style="width: 100%;" />
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>

                    <h2>متن‌های دکمه‌های دینامیک:</h2>
                    <div class="qrcdr-accordion">
                    <?php foreach ($dynamic_buttons as $btn): 
                        $title = get_option("qrcdr_dynamic_{$btn}_title_fa", $default_dynamic_texts['fa'][$btn]['title'] ?? ucfirst($btn));
                    ?>
                        <div class="qrcdr-acc-item">
                            <button type="button" class="qrcdr-acc-header" onclick="qrcdrToggleAccordion(this)">
                                <span><?php echo esc_html($title); ?></span>
                                <span class="qrcdr-acc-arrow">▾</span>
                            </button>
                            <div class="qrcdr-acc-body" style="display: none;">
                                <div class="form-row">
                                    <label>عنوان اصلی:</label>
                                    <input type="text" name="messages_fa_dynamicButtons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_dynamicButtons', [])[$btn] ?? $title); ?>" style="width: 100%;" />
                                </div>
                                <div class="form-row">
                                    <label>زیرمتن:</label>
                                    <input type="text" name="messages_fa_dynamicButtons_subtext[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_dynamicButtons_subtext', [])[$btn] ?? ''); ?>" style="width: 100%;" />
                                </div>
                                <div class="form-row">
                                    <label>متن ثانویه:</label>
                                    <input type="text" name="messages_fa_dynamicButtons_text2[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_fa_dynamicButtons_text2', [])[$btn] ?? ''); ?>" style="width: 100%;" />
                                </div>
                                <div class="form-row">
                                    <label>مستندات:</label>
                                    <textarea name="messages_fa_dynamicButtons_doc[<?php echo $btn; ?>]" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('qrcdr_messages_fa_dynamicButtons_doc', [])[$btn] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- English Messages -->
                <div id="lang-en" class="lang-content" style="display: none;">
                    <h3>🇺🇸 English Texts</h3>
                    
                    <div class="form-row">
                        <label>Main Title:</label>
                        <input type="text" name="messages_en_title" value="<?php echo esc_attr(get_option('qrcdr_messages_en_title', $default_messages['en']['title'])); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Top Page Title:</label>
                        <input type="text" name="messages_en_topTitle" value="<?php echo esc_attr(get_option('qrcdr_messages_en_topTitle', $default_messages['en']['topTitle'])); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Top Page Text:</label>
                        <textarea name="messages_en_topText" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('qrcdr_messages_en_topText', $default_messages['en']['topText'])); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>Static Tab Text:</label>
                        <input type="text" name="messages_en_staticTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_en_staticTabText', $default_messages['en']['staticTabText'])); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Dynamic Tab Text:</label>
                        <input type="text" name="messages_en_dynamicTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_en_dynamicTabText', $default_messages['en']['dynamicTabText'])); ?>" style="width: 100%;" />
                    </div>
                    
                    <h4>User Interface Texts:</h4>
                    <div class="form-row">
                        <label>Generate QR Code:</label>
                        <input type="text" name="messages_en_generate_qrcode" value="<?php echo esc_attr(get_option('qrcdr_messages_en_generate_qrcode', 'Generate QR Code')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Error Correction Level:</label>
                        <input type="text" name="messages_en_error_correction_level" value="<?php echo esc_attr(get_option('qrcdr_messages_en_error_correction_level', 'Error Correction Level')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Frame Selection:</label>
                        <input type="text" name="messages_en_frame_selection" value="<?php echo esc_attr(get_option('qrcdr_messages_en_frame_selection', 'Frame Selection')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Color Selection:</label>
                        <input type="text" name="messages_en_color_selection" value="<?php echo esc_attr(get_option('qrcdr_messages_en_color_selection', 'Color Selection')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Visit Website:</label>
                        <input type="text" name="messages_en_visit_website" value="<?php echo esc_attr(get_option('qrcdr_messages_en_visit_website', 'Visit Website')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>More Info:</label>
                        <input type="text" name="messages_en_more_info" value="<?php echo esc_attr(get_option('qrcdr_messages_en_more_info', 'More Info')); ?>" style="width: 100%;" />
                    </div>

                    <h4>Static Button Texts:</h4>
                    <div class="qrcdr-accordion">
                    <?php foreach ($static_buttons as $btn): ?>
                        <div class="qrcdr-acc-item">
                            <button type="button" class="qrcdr-acc-header" onclick="qrcdrToggleAccordion(this)">
                                <span><?php echo ucfirst($btn); ?></span>
                                <span class="qrcdr-acc-arrow">▾</span>
                            </button>
                            <div class="qrcdr-acc-body" style="display: none;">
                                <div class="form-row">
                                    <label><?php echo ucfirst($btn); ?> Button:</label>
                                    <input type="text" name="messages_en_buttons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_en_buttons', [])[$btn] ?? ucfirst($btn) . " QR Code"); ?>" style="width: 100%;" />
                                </div>
                                <div class="form-row">
                                    <label><?php echo ucfirst($btn); ?> Help Text:</label>
                                    <input type="text" name="messages_en_buttons_text[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_en_buttons_text', [])[$btn] ?? "Please Enter " . ucfirst($btn) . " for QR Code"); ?>" style="width: 100%;" />
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>

                    <h4>Dynamic Button Texts:</h4>
                    <div class="qrcdr-accordion">
                    <?php foreach ($dynamic_buttons as $btn): 
                        $title = get_option("qrcdr_dynamic_{$btn}_title_en", ucfirst($btn));
                    ?>
                        <div class="qrcdr-acc-item">
                            <button type="button" class="qrcdr-acc-header" onclick="qrcdrToggleAccordion(this)">
                                <span><?php echo esc_html(ucfirst($title)); ?></span>
                                <span class="qrcdr-acc-arrow">▾</span>
                            </button>
                            <div class="qrcdr-acc-body" style="display: none;">
                                <div class="form-row">
                                    <label>Main Title:</label>
                                    <input type="text" name="messages_en_dynamicButtons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_en_dynamicButtons', [])[$btn] ?? ucfirst($btn)); ?>" style="width: 100%;" />
                                </div>
                                <div class="form-row">
                                    <label>Subtext:</label>
                                    <input type="text" name="messages_en_dynamicButtons_subtext[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_en_dynamicButtons_subtext', [])[$btn] ?? ''); ?>" style="width: 100%;" />
                                </div>
                                <div class="form-row">
                                    <label>Secondary Text:</label>
                                    <input type="text" name="messages_en_dynamicButtons_text2[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_en_dynamicButtons_text2', [])[$btn] ?? ''); ?>" style="width: 100%;" />
                                </div>
                                <div class="form-row">
                                    <label>Documentation:</label>
                                    <textarea name="messages_en_dynamicButtons_doc[<?php echo $btn; ?>]" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('qrcdr_messages_en_dynamicButtons_doc', [])[$btn] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Arabic Messages -->
                <div id="lang-ar" class="lang-content" style="display: none;">
                    <h3>🇸🇦 النصوص العربية</h3>
                    
                    <div class="form-row">
                        <label>العنوان الرئيسي:</label>
                        <input type="text" name="messages_ar_title" value="<?php echo esc_attr(get_option('qrcdr_messages_ar_title', 'مولد رمز QR')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>عنوان الصفحة العلوي:</label>
                        <input type="text" name="messages_ar_topTitle" value="<?php echo esc_attr(get_option('qrcdr_messages_ar_topTitle', 'مولد رمز QR عبر الإنترنت')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>نص الصفحة العلوي:</label>
                        <textarea name="messages_ar_topText" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('messages_ar_topText', 'مولد رمز QR عبر الإنترنت مع واجهة سهلة الاستخدام')); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>نص تب ثابت:</label>
                        <input type="text" name="messages_ar_staticTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_ar_staticTabText', 'إنشاء رمز QR ثابت')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>نص تب ديناميكي:</label>
                        <input type="text" name="messages_ar_dynamicTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_ar_dynamicTabText', 'إنشاء رمز QR ديناميكي')); ?>" style="width: 100%;" />
                    </div>
                    
                    <h4>نصوص الأزرار الثابتة:</h4>
                    <?php foreach ($static_buttons as $btn): ?>
                        <div class="form-row">
                            <label>زر <?php echo $btn; ?>:</label>
                            <input type="text" name="messages_ar_buttons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_ar_buttons', [])[$btn] ?? "رمز QR {$btn}"); ?>" style="width: 100%;" />
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Turkish Messages -->
                <div id="lang-tr" class="lang-content" style="display: none;">
                    <h3>🇹🇷 Türkçe Metinler</h3>
                    
                    <div class="form-row">
                        <label>Ana Başlık:</label>
                        <input type="text" name="messages_tr_title" value="<?php echo esc_attr(get_option('qrcdr_messages_tr_title', 'QR Kod Üretici')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Üst Sayfa Başlığı:</label>
                        <input type="text" name="messages_tr_topTitle" value="<?php echo esc_attr(get_option('qrcdr_messages_tr_topTitle', 'Çevrimiçi QR Kod Üretici')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Üst Sayfa Metni:</label>
                        <textarea name="messages_tr_topText" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('messages_tr_topText', 'Kullanıcı dostu arayüz ile çevrimiçi QR kod üretici')); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>Statik Sekme Metni:</label>
                        <input type="text" name="messages_tr_staticTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_tr_staticTabText', 'Statik QR Kod Oluştur')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Dinamik Sekme Metni:</label>
                        <input type="text" name="messages_tr_dynamicTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_tr_dynamicTabText', 'Dinamik QR Kod Oluştur')); ?>" style="width: 100%;" />
                    </div>
                    
                    <h4>Statik Düğme Metinleri:</h4>
                    <?php foreach ($static_buttons as $btn): ?>
                        <div class="form-row">
                            <label><?php echo ucfirst($btn); ?> Düğmesi:</label>
                            <input type="text" name="messages_tr_buttons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_tr_buttons', [])[$btn] ?? ucfirst($btn) . " QR Kodu"); ?>" style="width: 100%;" />
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Russian Messages -->
                <div id="lang-ru" class="lang-content" style="display: none;">
                    <h3>🇷🇺 Русские тексты</h3>
                    
                    <div class="form-row">
                        <label>Главный заголовок:</label>
                        <input type="text" name="messages_ru_title" value="<?php echo esc_attr(get_option('qrcdr_messages_ru_title', 'Генератор QR-кодов')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Заголовок верхней страницы:</label>
                        <input type="text" name="messages_ru_topTitle" value="<?php echo esc_attr(get_option('qrcdr_messages_ru_topTitle', 'Онлайн генератор QR-кодов')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Текст верхней страницы:</label>
                        <textarea name="messages_ru_topText" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('messages_ru_topText', 'Онлайн генератор QR-кодов с удобным интерфейсом')); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>Текст статичной вкладки:</label>
                        <input type="text" name="messages_ru_staticTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_ru_staticTabText', 'Создать статичный QR-код')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Текст динамичной вкладки:</label>
                        <input type="text" name="messages_ru_dynamicTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_ru_dynamicTabText', 'Создать динамичный QR-код')); ?>" style="width: 100%;" />
                    </div>
                    
                    <h4>Тексты статичных кнопок:</h4>
                    <?php foreach ($static_buttons as $btn): ?>
                        <div class="form-row">
                            <label>Кнопка <?php echo $btn; ?>:</label>
                            <input type="text" name="messages_ru_buttons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_ru_buttons', [])[$btn] ?? "QR-код {$btn}"); ?>" style="width: 100%;" />
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- French Messages -->
                <div id="lang-fr" class="lang-content" style="display: none;">
                    <h3>🇫🇷 Textes français</h3>
                    
                    <div class="form-row">
                        <label>Titre principal:</label>
                        <input type="text" name="messages_fr_title" value="<?php echo esc_attr(get_option('qrcdr_messages_fr_title', 'Générateur de codes QR')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Titre de la page supérieure:</label>
                        <input type="text" name="messages_fr_topTitle" value="<?php echo esc_attr(get_option('qrcdr_messages_fr_topTitle', 'Générateur de codes QR en ligne')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Texte de la page supérieure:</label>
                        <textarea name="messages_fr_topText" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('messages_fr_topText', 'Générateur de codes QR en ligne avec une interface conviviale')); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>Texte de l\'onglet statique:</label>
                        <input type="text" name="messages_fr_staticTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_fr_staticTabText', 'Créer un code QR statique')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Texte de l\'onglet dynamique:</label>
                        <input type="text" name="messages_fr_dynamicTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_fr_dynamicTabText', 'Créer un code QR dynamique')); ?>" style="width: 100%;" />
                    </div>
                    
                    <h4>Textes des boutons statiques:</h4>
                    <?php foreach ($static_buttons as $btn): ?>
                        <div class="form-row">
                            <label>Bouton <?php echo $btn; ?>:</label>
                            <input type="text" name="messages_fr_buttons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_fr_buttons', [])[$btn] ?? "Code QR {$btn}"); ?>" style="width: 100%;" />
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- German Messages -->
                <div id="lang-de" class="lang-content" style="display: none;">
                    <h3>🇩🇪 Deutsche Texte</h3>
                    
                    <div class="form-row">
                        <label>Haupttitel:</label>
                        <input type="text" name="messages_de_title" value="<?php echo esc_attr(get_option('qrcdr_messages_de_title', 'QR-Code Generator')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Obere Seitentitel:</label>
                        <input type="text" name="messages_de_topTitle" value="<?php echo esc_attr(get_option('qrcdr_messages_de_topTitle', 'Online QR-Code Generator')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Obere Seitentext:</label>
                        <textarea name="messages_de_topText" style="width: 100%; height: 80px;"><?php echo esc_textarea(get_option('messages_de_topText', 'Online QR-Code Generator mit benutzerfreundlicher Oberfläche')); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>Statischer Tab-Text:</label>
                        <input type="text" name="messages_de_staticTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_de_staticTabText', 'Statischen QR-Code erstellen')); ?>" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>Dynamischer Tab-Text:</label>
                        <input type="text" name="messages_de_dynamicTabText" value="<?php echo esc_attr(get_option('qrcdr_messages_de_dynamicTabText', 'Dynamischen QR-Code erstellen')); ?>" style="width: 100%;" />
                    </div>
                    
                    <h4>Statische Schaltflächentexte:</h4>
                    <?php foreach ($static_buttons as $btn): ?>
                        <div class="form-row">
                            <label><?php echo ucfirst($btn); ?> Schaltfläche:</label>
                            <input type="text" name="messages_de_buttons[<?php echo $btn; ?>]" value="<?php echo esc_attr(get_option('qrcdr_messages_de_buttons', [])[$btn] ?? ucfirst($btn) . " QR-Code"); ?>" style="width: 100%;" />
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <input type="submit" value="<?php echo esc_attr($admin_text['save_settings']); ?>" class="button button-primary" />
            </form>
        </div>

        <!-- Appearance Tab -->
        <div id="appearance" class="tab-content <?php echo $active_tab === 'appearance' ? 'active' : ''; ?>">
            <form method="POST">
                <?php wp_nonce_field('qrcdr_settings_nonce', 'qrcdr_nonce'); ?>
                <input type="hidden" name="active_tab" value="appearance">
                
                <?php 
                // Ensure current_lang is defined for this tab
                if (!isset($current_lang)) { 
                    $current_lang = qrcdr_get_wp_language(); 
                } 
                ?>
                
                <h2><?php echo $current_lang === 'fa' ? 'تنظیمات ظاهری' : ($current_lang === 'ar' ? 'إعدادات المظهر' : 'Appearance Settings'); ?></h2>
                <p><?php echo $current_lang === 'fa' ? 'در این بخش می‌توانید ظاهر پلاگین را تنظیم کنید:' : ($current_lang === 'ar' ? 'في هذا القسم يمكنك تخصيص مظهر الإضافة:' : 'In this section you can customize the appearance of the plugin:'); ?></p>
            <form method="POST">
                <?php wp_nonce_field('qrcdr_settings_nonce', 'qrcdr_nonce'); ?>
                <input type="hidden" name="active_tab" value="appearance">

                <?php 
                // Ensure current_lang is defined for this tab
                if (!isset($current_lang)) {
                    $current_lang = qrcdr_get_wp_language();
                }
                ?>

                <h2 style="text-align: <?php echo $current_lang === 'fa' || $current_lang === 'ar' ? 'right' : 'left'; ?>;"><?php echo $current_lang === 'fa' ? 'تنظیمات فونت' : ($current_lang === 'ar' ? 'إعدادات الخط' : 'Font Settings'); ?></h2>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'فونت اصلی پلاگین:' : ($current_lang === 'ar' ? 'الخط الرئيسي للإضافة:' : 'Main Plugin Font:'); ?></label>
                    <select name="main_font_family" style="width: 200px;">
                        <option value="Vazir" <?php selected($main_font_family, 'Vazir'); ?>><?php echo $current_lang === 'fa' ? 'Vazir (پیش‌فرض)' : ($current_lang === 'ar' ? 'Vazir (افتراضي)' : 'Vazir (Default)'); ?></option>
                        <option value="Tahoma" <?php selected($main_font_family, 'Tahoma'); ?>>Tahoma</option>
                        <option value="Arial" <?php selected($main_font_family, 'Arial'); ?>>Arial</option>
                        <option value="Roboto" <?php selected($main_font_family, 'Roboto'); ?>>Roboto</option>
                        <option value="Open Sans" <?php selected($main_font_family, 'Open Sans'); ?>>Open Sans</option>
                        <option value="IRANSans" <?php selected($main_font_family, 'IRANSans'); ?>>IRANSans</option>
                        <option value="Yekan" <?php selected($main_font_family, 'Yekan'); ?>>Yekan</option>
                        <option value="custom" <?php selected($main_font_family, 'custom'); ?>><?php echo $current_lang === 'fa' ? 'فونت سفارشی' : ($current_lang === 'ar' ? 'خط مخصص' : 'Custom Font'); ?></option>
                    </select>
                </div>
                
                <div class="form-row" id="custom-font-row" style="<?php echo $main_font_family !== 'custom' ? 'display: none;' : ''; ?>">
                    <label><?php echo $current_lang === 'fa' ? 'URL فونت سفارشی:' : ($current_lang === 'ar' ? 'رابط الخط المخصص:' : 'Custom Font URL:'); ?></label>
                    <input type="url" name="custom_font_url" value="<?php echo esc_attr($custom_font_url); ?>" placeholder="https://fonts.googleapis.com/css2?family=..." style="width: 100%;" />
                    <small><?php echo $current_lang === 'fa' ? 'مثال: https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap' : ($current_lang === 'ar' ? 'مثال: https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap' : 'Example: https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap'); ?></small>
                </div>
                
                <h2><?php echo $current_lang === 'fa' ? 'تنظیمات پس‌زمینه' : ($current_lang === 'ar' ? 'إعدادات الخلفية' : 'Background Settings'); ?></h2>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'نوع پس‌زمینه:' : ($current_lang === 'ar' ? 'نوع الخلفية:' : 'Background Type:'); ?></label>
                    <select name="background_type" onchange="toggleBackgroundOptions(this.value)">
                        <option value="color" <?php selected($background_type, 'color'); ?>><?php echo $current_lang === 'fa' ? 'رنگ یکنواخت' : ($current_lang === 'ar' ? 'لون موحد' : 'Solid Color'); ?></option>
                        <option value="image" <?php selected($background_type, 'image'); ?>><?php echo $current_lang === 'fa' ? 'تصویر' : ($current_lang === 'ar' ? 'صورة' : 'Image'); ?></option>
                        <option value="transparent" <?php selected($background_type, 'transparent'); ?>><?php echo $current_lang === 'fa' ? 'شفاف' : ($current_lang === 'ar' ? 'شفاف' : 'Transparent'); ?></option>
                        <option value="gradient" <?php selected($background_type, 'gradient'); ?>><?php echo $current_lang === 'fa' ? 'گرادیان' : ($current_lang === 'ar' ? 'تدرج' : 'Gradient'); ?></option>
                    </select>
                </div>
                
                <div class="form-row" id="bg-image-row" style="<?php echo $background_type !== 'image' ? 'display: none;' : ''; ?>">
                    <label><?php echo $current_lang === 'fa' ? 'تصویر پس‌زمینه:' : ($current_lang === 'ar' ? 'صورة الخلفية:' : 'Background Image:'); ?></label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" name="background_image" id="background_image" value="<?php echo esc_attr($background_image); ?>" placeholder="<?php echo $current_lang === 'fa' ? 'URL تصویر یا انتخاب از سیستم' : ($current_lang === 'ar' ? 'رابط الصورة أو اختيار من النظام' : 'Image URL or select from system'); ?>" style="flex: 1;" />
                        <button type="button" class="button qrcdr-image-selector"><?php echo $current_lang === 'fa' ? 'انتخاب تصویر' : ($current_lang === 'ar' ? 'اختيار صورة' : 'Select Image'); ?></button>
                    </div>
                    <div class="qrcdr-image-preview" style="display: <?php echo $background_image ? 'block' : 'none'; ?>; margin-top: 15px;">
                        <?php if ($background_image): ?>
                            <img src="<?php echo esc_url($background_image); ?>" alt="<?php echo $current_lang === 'fa' ? 'تصویر پس‌زمینه' : ($current_lang === 'ar' ? 'صورة الخلفية' : 'Background Image'); ?>" style="max-width: 250px; max-height: 180px; border: 3px solid #ddd; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);" />
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-row" id="bg-transparency-row" style="<?php echo $background_type === 'color' ? 'display: none;' : ''; ?>">
                    <label><?php echo $current_lang === 'fa' ? 'شفافیت پس‌زمینه:' : ($current_lang === 'ar' ? 'شفافية الخلفية:' : 'Background Transparency:'); ?></label>
                    <input type="range" name="background_transparency" min="0" max="100" value="<?php echo esc_attr($background_transparency); ?>" oninput="updateTransparencyValue(this.value)" />
                    <span id="transparency-value"><?php echo esc_html($background_transparency); ?>%</span>
                </div>
                
                <div class="form-row" id="bg-gradient-row" style="<?php echo $background_type !== 'gradient' ? 'display: none;' : ''; ?>">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ‌های گرادیان:' : ($current_lang === 'ar' ? 'ألوان التدرج:' : 'Gradient Colors:'); ?></label>
                    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                        <div class="color-input-group">
                            <label style="font-size: 12px; margin-bottom: 5px;"><?php echo $current_lang === 'fa' ? 'رنگ اول:' : ($current_lang === 'ar' ? 'اللون الأول:' : 'First Color:'); ?></label>
                            <input type="color" name="gradient_color_1" value="<?php echo esc_attr(get_option('qrcdr_gradient_color_1', '#ffffff')); ?>" onchange="updateGradientPreview()" class="qrcdr-color-picker" data-preview="gradient-color1-preview" />
                            <div id="gradient-color1-preview" class="color-preview" style="background-color: <?php echo esc_attr(get_option('qrcdr_gradient_color_1', '#ffffff')); ?>; width: 40px; height: 40px; border: 1px solid #ddd; border-radius: 6px; margin: 2px 0;"></div>
                            <span class="color-code"><?php echo esc_html(get_option('qrcdr_gradient_color_1', '#ffffff')); ?></span>
                        </div>
                        
                        <div class="color-input-group">
                            <label style="font-size: 12px; margin-bottom: 5px;"><?php echo $current_lang === 'fa' ? 'رنگ دوم:' : ($current_lang === 'ar' ? 'اللون الثاني:' : 'Second Color:'); ?></label>
                            <input type="color" name="gradient_color_2" value="<?php echo esc_attr(get_option('qrcdr_gradient_color_2', '#403199')); ?>" onchange="updateGradientPreview()" class="qrcdr-color-picker" data-preview="gradient-color2-preview" />
                            <div id="gradient-color2-preview" class="color-preview" style="background-color: <?php echo esc_attr(get_option('qrcdr_gradient_color_2', '#403199')); ?>; width: 40px; height: 40px; border: 1px solid #ddd; border-radius: 6px; margin: 2px 0;"></div>
                            <span class="color-code"><?php echo esc_html(get_option('qrcdr_gradient_color_2', '#403199')); ?></span>
                        </div>
                        
                        <div class="color-input-group">
                            <label style="font-size: 12px; margin-bottom: 5px;"><?php echo $current_lang === 'fa' ? 'جهت گرادیان:' : ($current_lang === 'ar' ? 'اتجاه التدرج:' : 'Gradient Direction:'); ?></label>
                            <select name="gradient_direction" onchange="updateGradientPreview()" style="width: 120px;">
                                <option value="135deg" <?php selected(get_option('qrcdr_gradient_direction', '135deg'), '135deg'); ?>><?php echo $current_lang === 'fa' ? 'مورب (135°)' : ($current_lang === 'ar' ? 'مائل (135°)' : 'Diagonal (135°)'); ?></option>
                                <option value="90deg" <?php selected(get_option('qrcdr_gradient_direction', '135deg'), '90deg'); ?>><?php echo $current_lang === 'fa' ? 'عمودی (90°)' : ($current_lang === 'ar' ? 'عمودي (90°)' : 'Vertical (90°)'); ?></option>
                                <option value="0deg" <?php selected(get_option('qrcdr_gradient_direction', '135deg'), '0deg'); ?>><?php echo $current_lang === 'fa' ? 'افقی (0°)' : ($current_lang === 'ar' ? 'أفقي (0°)' : 'Horizontal (0°)'); ?></option>
                                <option value="45deg" <?php selected(get_option('qrcdr_gradient_direction', '135deg'), '45deg'); ?>><?php echo $current_lang === 'fa' ? 'مورب (45°)' : ($current_lang === 'ar' ? 'مائل (45°)' : 'Diagonal (45°)'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <label style="font-size: 12px; margin-bottom: 5px;"><?php echo $current_lang === 'fa' ? 'پیش‌نمایش گرادیان:' : ($current_lang === 'ar' ? 'معاينة التدرج:' : 'Gradient Preview:'); ?></label>
                        <div id="gradient-preview" class="qrcdr-gradient-preview" style="width: 100%; height: 80px; border: 3px solid #ddd; border-radius: 10px; background: linear-gradient(<?php echo esc_attr(get_option('qrcdr_gradient_direction', '135deg')); ?>, <?php echo esc_attr(get_option('qrcdr_gradient_color_1', '#ffffff')); ?>, <?php echo esc_attr(get_option('qrcdr_gradient_color_2', '#403199')); ?>);"></div>
                    </div>
                </div>

            <h2><?php echo $current_lang === 'fa' ? 'تنظیمات رنگ‌ها' : ($current_lang === 'ar' ? 'إعدادات الألوان' : 'Color Settings'); ?></h2>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ پس‌زمینه اصلی:' : ($current_lang === 'ar' ? 'لون الخلفية الرئيسي:' : 'Main Background Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="orgin_bg_color" value="<?php echo esc_attr($orgin_bg_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="main-bg-preview" />
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ پس‌زمینه منو:' : ($current_lang === 'ar' ? 'لون خلفية القائمة:' : 'Menu Background Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="menu_bg_color" value="<?php echo esc_attr($menu_bg_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="menu-bg-preview" />
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ منوی فعال:' : ($current_lang === 'ar' ? 'لون القائمة النشطة:' : 'Active Menu Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="active_menu_color" value="<?php echo esc_attr($active_menu_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="active-menu-preview" />
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ آیکون:' : ($current_lang === 'ar' ? 'لون الأيقونة:' : 'Icon Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="icon_color" value="<?php echo esc_attr($icon_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="icon-color-preview" />
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ پس‌زمینه بالا:' : ($current_lang === 'ar' ? 'لون خلفية الأعلى:' : 'Top Background Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="top_bg_color" value="<?php echo esc_attr($top_bg_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="top-bg-preview" />
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ پس‌زمینه تب:' : ($current_lang === 'ar' ? 'لون خلفية التبويب:' : 'Tab Background Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="tab_bg_color" value="<?php echo esc_attr($tab_bg_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="tab-bg-preview" />
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ تب فعال:' : ($current_lang === 'ar' ? 'لون التبويب النشط:' : 'Active Tab Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="active_tab_color" value="<?php echo esc_attr($active_tab_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="active-tab-preview" />
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ دکمه:' : ($current_lang === 'ar' ? 'لون الزر:' : 'Button Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="button_color" value="<?php echo esc_attr($button_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="button-color-preview" />
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ پس‌زمینه آیکون:' : ($current_lang === 'ar' ? 'لون خلفية الأيقونة:' : 'Icon Background Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="icon_bg_color" value="<?php echo esc_attr($icon_bg_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="icon-bg-preview" />
                                <!-- <div class="color-preview" style="background-color: <?php echo esc_attr($icon_bg_color); ?>; width: 40px; height: 40px; border: 1px solid #ddd; border-radius: 6px; margin: 2px 0;"></div>
                                <span class="color-code"><?php echo esc_html($icon_bg_color); ?></span> -->
                    </div>
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'رنگ خط پایین:' : ($current_lang === 'ar' ? 'لون الخط السفلي:' : 'Bottom Line Color:'); ?></label>
                    <div class="color-picker-field">
                        <input type="color" name="bottom_line_color" value="<?php echo esc_attr($bottom_line_color); ?>" onchange="updateColorPreview(this)" class="color-picker-input" data-preview="bottom-line-preview" />
                    </div>
                </div>
                
                <h2><?php echo $current_lang === 'fa' ? 'تنظیمات اندازه' : ($current_lang === 'ar' ? 'إعدادات الحجم' : 'Size Settings'); ?></h2>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'اندازه تب:' : ($current_lang === 'ar' ? 'حجم التبويب:' : 'Tab Size:'); ?></label>
                    <input type="text" name="tab_size" value="<?php echo esc_attr($tab_size); ?>" placeholder="50%" />
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'اندازه متن:' : ($current_lang === 'ar' ? 'حجم النص:' : 'Text Size:'); ?></label>
                    <input type="text" name="text_size" value="<?php echo esc_attr($text_size); ?>" placeholder="12px" />
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'اندازه متن تب:' : ($current_lang === 'ar' ? 'حجم نص التبويب:' : 'Tab Text Size:'); ?></label>
                    <input type="text" name="tab_text_size" value="<?php echo esc_attr($tab_text_size); ?>" placeholder="16px" />
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'اندازه متن تب فعال:' : ($current_lang === 'ar' ? 'حجم نص التبويب النشط:' : 'Active Tab Text Size:'); ?></label>
                    <input type="text" name="tab_text_active_size" value="<?php echo esc_attr($tab_text_active_size); ?>" placeholder="17px" />
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'اندازه دکمه:' : ($current_lang === 'ar' ? 'حجم الزر:' : 'Button Size:'); ?></label>
                    <input type="text" name="button_size" value="<?php echo esc_attr($button_size); ?>" placeholder="100px" />
                </div>
                
                <div class="form-row">
                    <label><?php echo $current_lang === 'fa' ? 'اندازه آیکون:' : ($current_lang === 'ar' ? 'حجم الأيقونة:' : 'Icon Size:'); ?></label>
                    <input type="text" name="icon_size" value="<?php echo esc_attr($icon_size); ?>" placeholder="12px" />
                </div>
                
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <input type="submit" value="<?php echo esc_attr($admin_text['save_settings']); ?>" class="button button-primary" />
                    <button type="button" class="button button-secondary" onclick="resetAppearanceSettings()" style="margin-left: 10px;">
                        <?php echo $current_lang === 'fa' ? 'بازگردانی تنظیمات ظاهری' : ($current_lang === 'ar' ? 'إعادة تعيين إعدادات المظهر' : 'Reset Appearance Settings'); ?>
                    </button>
                    <button type="button" class="button button-secondary" onclick="resetAllSettings()" style="margin-left: 10px; background-color: #dc3545; border-color: #dc3545; color: white;">
                        <?php echo $current_lang === 'fa' ? 'بازگردانی کل تنظیمات' : ($current_lang === 'ar' ? 'إعادة تعيين جميع الإعدادات' : 'Reset All Settings'); ?>
                    </button>
                </div>
        </form>
        </div>
        

        
        <!-- Shortcode Section -->
        <div class="copy-shortcode">
            <h2><?php echo $current_lang === 'fa' ? 'کپی شورتکد' : ($current_lang === 'ar' ? 'نسخ الكود المختصر' : 'Copy Shortcode'); ?></h2>
            <textarea id="shortcode" readonly>[qrcdr]</textarea>
            <button onclick="copyShortcode('shortcode')"><?php echo $current_lang === 'fa' ? 'کپی کردن شورتکد' : ($current_lang === 'ar' ? 'نسخ الكود المختصر' : 'Copy Shortcode'); ?></button>
            <span id="copy-success" class="copy-success" style="display: none;"><?php echo $current_lang === 'fa' ? 'شورتکد کپی شد!' : ($current_lang === 'ar' ? 'تم نسخ الكود المختصر!' : 'Shortcode copied!'); ?></span>

            <textarea id="shortcode-alternate" readonly>[qrcdr_alternate width="100%" height="100vh"]</textarea>
            <button onclick="copyShortcode('shortcode-alternate')"><?php echo $current_lang === 'fa' ? 'کپی کردن شورتکد' : ($current_lang === 'ar' ? 'نسخ الكود المختصر' : 'Copy Shortcode'); ?></button>
            <span id="copy-success-alternate" class="copy-success" style="display: none;"><?php echo $current_lang === 'fa' ? 'شورتکد کپی شد!' : ($current_lang === 'ar' ? 'تم نسخ الكود المختصر!' : 'Shortcode copied!'); ?></span>
        </div>
    </div>

    <script>
        document.currentLang = '<?php echo $current_lang; ?>';
        function copyShortcode(elementId) {
            const shortcodeField = document.getElementById(elementId);
            const successMessage = document.getElementById(`copy-success${elementId === 'shortcode-alternate' ? '-alternate' : ''}`);

            shortcodeField.select();
            shortcodeField.setSelectionRange(0, 99999);

            document.execCommand('copy');

            successMessage.style.display = 'inline';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 2000);
        }

        // Color preview update
        function updateColorPreview(input) {
            const colorInputGroup = input.parentElement;
            const preview = colorInputGroup.querySelector('.color-preview');
            const codeSpan = colorInputGroup.querySelector('.color-code');
            
            if (preview) {
                preview.style.backgroundColor = input.value;
            }
            if (codeSpan) {
                codeSpan.textContent = input.value;
            }
        }

        // Language tab switching
        function showLanguageTab(lang, event) {
            // Hide all language content
            document.querySelectorAll('.lang-content').forEach(content => {
                if (content.style.display !== 'none') {
            content.style.opacity = '0';
            content.style.transform = 'translateY(10px)';
            setTimeout(() => {
                content.style.display = 'none';
            }, 150);
        }
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.lang-tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected language content
            const targetContent = document.getElementById(`lang-${lang}`);
            if (targetContent) {
        setTimeout(() => {
            targetContent.style.display = 'block';
            setTimeout(() => {
                targetContent.style.opacity = '1';
                targetContent.style.transform = 'translateY(0)';
            }, 10);
        }, 150);
    }
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }

        // Font selection handler
        document.addEventListener('DOMContentLoaded', function() {
            const fontSelect = document.querySelector('select[name="main_font_family"]');
            const customFontRow = document.getElementById('custom-font-row');
            
            if (fontSelect) {
                fontSelect.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customFontRow.style.display = 'block';
                    } else {
                        customFontRow.style.display = 'none';
                    }
                });
            }
        });

        // Background options toggle
        function toggleBackgroundOptions(type) {
            const imageRow = document.getElementById('bg-image-row');
            const transparencyRow = document.getElementById('bg-transparency-row');
            const gradientRow = document.getElementById('bg-gradient-row');
            
            // Hide all first
            imageRow.style.display = 'none';
            transparencyRow.style.display = 'none';
            gradientRow.style.display = 'none';
            
            // Show relevant options based on type
            switch(type) {
                case 'image':
                    imageRow.style.display = 'block';
                    transparencyRow.style.display = 'block';
                    break;
                case 'transparent':
                    transparencyRow.style.display = 'block';
                    break;
                case 'gradient':
                    transparencyRow.style.display = 'block';
                    gradientRow.style.display = 'block';
                    break;
            }
        }
        
        // Update gradient preview
        function updateGradientPreview() {
            const color1 = document.querySelector('input[name="gradient_color_1"]').value;
            const color2 = document.querySelector('input[name="gradient_color_2"]').value;
            const direction = document.querySelector('select[name="gradient_direction"]').value;
            
            const preview = document.getElementById('gradient-preview');
            if (preview) {
                preview.style.background = `linear-gradient(${direction}, ${color1}, ${color2})`;
            }
        }
        
        // Static language tab switching
        function showStaticLanguageTab(lang, event) {
            // Hide all static language content
            document.querySelectorAll('.static-lang-content').forEach(content => {
                if (content.style.display !== 'none') {
            content.style.opacity = '0';
            content.style.transform = 'translateY(10px)';
            setTimeout(() => {
                content.style.display = 'none';
            }, 150);
        }
    });
            
            // Remove active class from all buttons
            document.querySelectorAll('#static .lang-tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected language content with fade effect
    const targetContent = document.getElementById(`static-lang-${lang}`);
    if (targetContent) {
        setTimeout(() => {
            targetContent.style.display = 'block';
            setTimeout(() => {
                targetContent.style.opacity = '1';
                targetContent.style.transform = 'translateY(0)';
            }, 10);
        }, 150);
    }
     
            // Add active class to clicked button
            event.target.classList.add('active');
        }
        
        // Dynamic language tab switching
        function showDynamicLanguageTab(button, lang, event) {
            console.log('showDynamicLanguageTab called:', button, lang, event);
            
            // Validate parameters
            if (!button || !lang || !event) {
                console.error('Invalid parameters:', { button, lang, event });
                return;
            }
            
            // Hide all dynamic language content for this button
            const allContent = document.querySelectorAll(`[id^="dynamic-${button}-"]`);
            if (allContent.length === 0) {
                console.warn(`No content elements found for button: ${button}`);
            }
            
            allContent.forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });
            
            // Remove active class from all buttons in this button group
            const buttonGroup = event.target.closest('.language-tabs');
            if (buttonGroup) {
                const langButtons = buttonGroup.querySelectorAll('.lang-tab-btn');
                langButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                console.log(`Removed active class from ${langButtons.length} language buttons`);
            } else {
                console.warn('Language tabs container not found');
            }
            
            // Show selected language content
            const contentElement = document.getElementById(`dynamic-${button}-${lang}`);
            if (contentElement) {
                contentElement.style.display = 'block';
                contentElement.classList.add('active');
                
                // Add smooth transition
                contentElement.style.opacity = '0';
                contentElement.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    contentElement.style.opacity = '1';
                    contentElement.style.transform = 'translateY(0)';
                }, 10);
                
                console.log(`Showed content for ${button}-${lang}`);
            } else {
                console.error(`Content element not found: dynamic-${button}-${lang}`);
            }
            
            // Add active class to clicked button
            try {
                event.target.classList.add('active');
                console.log(`Added active class to button for language: ${lang}`);
            } catch (error) {
                console.error('Error adding active class:', error);
            }
        }
        // Update transparency value display
        function updateTransparencyValue(value) {
            document.getElementById('transparency-value').textContent = value + '%';
        }

        function selectBackgroundImage() {
    // WordPress Media Library integration
    if (typeof wp !== 'undefined' && wp.media) {
        const translations = {
            fa: {
                title: 'انتخاب تصویر پس‌زمینه',
                button: 'انتخاب تصویر'
            },
            ar: {
                title: 'اختيار صورة الخلفية',
                button: 'اختيار الصورة'
            },
            en: {
                title: 'Select Background Image',
                button: 'Select Image'
            }
        };
        const lang = typeof currentLang !== 'undefined' ? currentLang : 'en';
        const mediaUploader = wp.media({
            title: translations[lang].title || translations['en'].title,
            button: {
                text: translations[lang].button || translations['en'].button
            },
            multiple: false
        });
                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    document.getElementById('background_image').value = attachment.url;
                    
                    // Update preview if exists
                    const preview = document.querySelector('#bg-image-row img');
                    if (preview) {
                        preview.src = attachment.url;
                    } else {
                        // Create preview
                        const previewDiv = document.createElement('div');
                        previewDiv.style.marginTop = '10px';
                        previewDiv.innerHTML = `<img src="${attachment.url}" style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; border-radius: 4px;" />`;
                        document.querySelector('#bg-image-row').appendChild(previewDiv);
                    }
                });

                mediaUploader.open();
            } else {
                // Fallback for direct URL input
                const url = prompt('لطفاً URL تصویر را وارد کنید:');
                if (url) {
                    document.getElementById('background_image').value = url;
                }
            }
        }

        // New function for selecting dynamic button icons
        function selectIconImage(buttonName) {
            console.log('selectIconImage called with:', buttonName);
            
            // Store current button name for icon selection
            window.currentIconButton = buttonName;
            
            // Show icon popup
            showIconPopup();
        }
        
        // Function to show icon popup
        function showIconPopup() {
            const popup = document.getElementById('icon-popup');
            popup.style.display = 'flex';
            
            // Load icons
            loadIcons();
            
            // Focus search input
            setTimeout(() => {
                document.getElementById('icon-search-input').focus();
            }, 100);
        }
        
        // Function to close icon popup
        function closeIconPopup() {
            const popup = document.getElementById('icon-popup');
            popup.style.display = 'none';
            
            // Clear search
            document.getElementById('icon-search-input').value = '';
            
            // Clear selection
            const selectedItems = document.querySelectorAll('.icon-item.selected');
            selectedItems.forEach(item => item.classList.remove('selected'));
        }
        
        // Function to load icons
        function loadIcons() {
            const iconGrid = document.getElementById('icon-grid');
            
            // FontAwesome icons
            const defaultIcons = [
                { name: 'کارت ویزیت', class: 'fas fa-id-card', category: 'business' },
                { name: 'شبکه اجتماعی', class: 'fas fa-share-alt', category: 'social' },
                { name: 'گالری عکس', class: 'fas fa-images', category: 'photo' },
                { name: 'گالری ویدیو', class: 'fas fa-video', category: 'video' },
                { name: 'لینک', class: 'fas fa-link', category: 'link' },
                { name: 'وب‌سایت', class: 'fas fa-globe', category: 'website' },
                { name: 'منو', class: 'fas fa-bars', category: 'menu' },
                { name: 'بیمه', class: 'fas fa-shield-alt', category: 'insurance' },
                { name: 'فروشگاه', class: 'fas fa-shopping-cart', category: 'shop' },
                { name: 'رزرو', class: 'fas fa-calendar-check', category: 'booking' },
                { name: 'مجتمع', class: 'fas fa-building', category: 'complex' },
                { name: 'محصول', class: 'fas fa-box', category: 'product' },
                { name: 'برچسب', class: 'fas fa-tag', category: 'label' },
                { name: 'طلا', class: 'fas fa-gem', category: 'gold' },
                { name: 'کاتالوگ', class: 'fas fa-book', category: 'catalog' },
                { name: 'ستاره', class: 'fas fa-star', category: 'star' },
                { name: 'قلب', class: 'fas fa-heart', category: 'heart' },
                { name: 'چشم', class: 'fas fa-eye', category: 'eye' },
                { name: 'دست', class: 'fas fa-hand-point-up', category: 'hand' },
                { name: 'فلش', class: 'fas fa-arrow-right', category: 'arrow' },
                { name: 'چرخ دنده', class: 'fas fa-cog', category: 'gear' },
                { name: 'قفل', class: 'fas fa-lock', category: 'lock' },
                { name: 'کلید', class: 'fas fa-key', category: 'key' },
                { name: 'تلفن', class: 'fas fa-phone', category: 'phone' },
                { name: 'ایمیل', class: 'fas fa-envelope', category: 'email' },
                { name: 'موقعیت', class: 'fas fa-map-marker-alt', category: 'location' },
                { name: 'زمان', class: 'fas fa-clock', category: 'time' },
                { name: 'کاربر', class: 'fas fa-user', category: 'user' },
                { name: 'گروه', class: 'fas fa-users', category: 'group' },
                { name: 'تنظیمات', class: 'fas fa-cogs', category: 'settings' },
                { name: 'دانلود', class: 'fas fa-download', category: 'download' },
                { name: 'آپلود', class: 'fas fa-upload', category: 'upload' },
                { name: 'ویرایش', class: 'fas fa-edit', category: 'edit' },
                { name: 'حذف', class: 'fas fa-trash', category: 'delete' },
                { name: 'ذخیره', class: 'fas fa-save', category: 'save' },
                { name: 'باز کردن', class: 'fas fa-folder-open', category: 'folder' },
                { name: 'چاپ', class: 'fas fa-print', category: 'print' },
                { name: 'جستجو', class: 'fas fa-search', category: 'search' },
                { name: 'فیلتر', class: 'fas fa-filter', category: 'filter' },
                { name: 'مرتب‌سازی', class: 'fas fa-sort', category: 'sort' },
                { name: 'تازه‌سازی', class: 'fas fa-sync', category: 'refresh' },
                { name: 'بازگشت', class: 'fas fa-undo', category: 'undo' },
                { name: 'تکرار', class: 'fas fa-redo', category: 'redo' },
                { name: 'بله', class: 'fas fa-check', category: 'check' },
                { name: 'خیر', class: 'fas fa-times', category: 'times' },
                { name: 'هشدار', class: 'fas fa-exclamation-triangle', category: 'warning' },
                { name: 'اطلاعات', class: 'fas fa-info-circle', category: 'info' },
                { name: 'سوال', class: 'fas fa-question-circle', category: 'question' }
            ];
            
            iconGrid.innerHTML = '';
            
            defaultIcons.forEach(icon => {
                const iconItem = document.createElement('div');
                iconItem.className = 'icon-item';
                iconItem.onclick = () => selectIcon(icon);
                
                iconItem.innerHTML = `
                    <i class="${icon.class}" style="font-size: 40px; color: #0073aa; margin-bottom: 8px;"></i>
                    <span>${icon.name}</span>
                `;
                
                iconGrid.appendChild(iconItem);
            });
            
            // Add search functionality
            const searchInput = document.getElementById('icon-search-input');
            searchInput.oninput = function() {
                const searchTerm = this.value.toLowerCase();
                const iconItems = document.querySelectorAll('.icon-item');
                
                iconItems.forEach(item => {
                    const iconName = item.querySelector('span').textContent.toLowerCase();
                    if (iconName.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            };
        }
        
        // Function to select icon
        function selectIcon(icon) {
            const buttonName = window.currentIconButton;
            
            if (buttonName) {
                // Update input field with FontAwesome class
                const inputField = document.querySelector(`input[name="dynamic_${buttonName}_icon_image"]`);
                if (inputField) {
                    inputField.value = icon.class;
                    
                    // Update preview with FontAwesome icon
                    const previewDiv = inputField.closest('.media-upload-field').querySelector('.media-upload-preview');
                    if (previewDiv) {
                        previewDiv.innerHTML = `<i class="${icon.class}" style="font-size: 24px; color: #0073aa;"></i>`;
                    }
                }
                
                // Close popup
                closeIconPopup();
                
                // Show success message
                const message = document.currentLang === 'fa' ? `آیکن "${icon.name}" انتخاب شد!` : `Icon "${icon.name}" selected!`;
                alert(message);
            }
        }
        
        // Function to select sample image
        function selectSampleImage(buttonName) {
            console.log('selectSampleImage called with:', buttonName);
            
            if (typeof wp !== 'undefined' && wp.media) {
                const mediaUploader = wp.media({
                    title: 'انتخاب عکس نمونه دکمه',
                    button: {
                        text: 'انتخاب عکس'
                    },
                    multiple: false,
                    // تنظیمات برای جدا کردن از CSS پلاگین
                    frame: 'select',
                    // تنظیمات library برای محدود کردن نوع فایل
                    library: {
                        type: 'image'
                    }
                });
                
                // تنظیمات اضافی برای جدا کردن از CSS پلاگین
                mediaUploader.states.get('library').set('library', {
                    type: 'image'
                });
                
                // تنظیمات برای جلوگیری از اعمال CSS های خارجی
                mediaUploader.on('ready', function() {
                    // اضافه کردن CSS مخصوص برای پاپ‌آپ
                    const style = document.createElement('style');
                    style.id = 'qrcdr-media-popup-style';
                    style.textContent = `
                        .media-modal {
                            z-index: 100000 !important;
                        }
                        .media-modal .media-frame {
                            background: #fff !important;
                        }
                        .media-modal .media-frame-title {
                            background: #f1f1f1 !important;
                            color: #23282d !important;
                        }
                        .media-modal .media-frame-router {
                            background: #f1f1f1 !important;
                        }
                        .media-modal .media-frame-content {
                            background: #fff !important;
                        }
                        .media-modal .media-toolbar {
                            background: #f1f1f1 !important;
                            border-top: 1px solid #ddd !important;
                        }
                        .media-modal .media-toolbar-primary {
                            background: #0073aa !important;
                            border-color: #0073aa !important;
                        }
                        .media-modal .media-toolbar-primary:hover {
                            background: #005a87 !important;
                        }
                    `;
                    document.head.appendChild(style);
                });
                
                // پاک کردن CSS اضافه شده بعد از بستن پاپ‌آپ
                mediaUploader.on('close', function() {
                    const style = document.getElementById('qrcdr-media-popup-style');
                    if (style) {
                        style.remove();
                    }
                });
                
                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    const inputField = document.querySelector(`input[name="dynamic_${buttonName}_sample_image"]`);
                    const previewDiv = inputField.closest('.media-upload-field').querySelector('.media-upload-preview');
                    
                    if (inputField) {
                        inputField.value = attachment.url;
                    }
                    
                    if (previewDiv) {
                        previewDiv.innerHTML = `<img src="${attachment.url}" alt="عکس نمونه دکمه" style="max-width: 100px; max-height: 100px;" />`;
                    }
                    
                    // پاک کردن CSS بعد از انتخاب
                    const style = document.getElementById('qrcdr-media-popup-style');
                    if (style) {
                        style.remove();
                    }
                });
                
                mediaUploader.open();
            } else {
                const url = prompt('لطفاً URL عکس نمونه را وارد کنید:');
                if (url) {
                    const inputField = document.querySelector(`input[name="dynamic_${buttonName}_sample_image"]`);
                    if (inputField) {
                        inputField.value = url;
                    }
                }
            }
        }
        // Apply selected font to admin interface preview
        function previewFont(fontFamily) {
            const style = document.createElement('style');
            style.textContent = `
                .wrap { 
                    font-family: ${fontFamily}, 'Tahoma', sans-serif !important; 
                }
            `;
            document.head.appendChild(style);
        }

        // Enhanced tab switching for main tabs
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing tab switching');
            
            const navTabs = document.querySelectorAll('.nav-tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            console.log('Found nav tabs:', navTabs.length);
            console.log('Found tab contents:', tabContents.length);
            
            // Function to show tab
//             function showTab(tabId) {
//                 console.log('Showing tab:', tabId);
//                 console.log('=== showTab called ===');
//                 console.log('tabId:', tabId);
//                 console.log('All tab contents:', document.querySelectorAll('.tab-content'));
    
   
                
//                 // Hide all tab contents
//                 tabContents.forEach(content => {
//                     content.classList.remove('active');
//                     content.style.display = 'none';
                    
//                 });
                
//                 // Remove active from all tabs
//                 navTabs.forEach(tab => {
//                     tab.classList.remove('active');
//                 });
                
//                 // Show target tab content
//                 const targetContent = document.getElementById(tabId);
//                 if (targetContent) {
//                     targetContent.classList.add('active');
//                     targetContent.style.display = 'block';
//                     console.log('Tab content shown:', tabId);
//                 } else {
//                     console.error('Tab content not found:', tabId);
//                 }
                
//                 // Activate corresponding tab
//                 const targetTab = document.querySelector(`[href*="tab=${tabId}"]`);
//                 if (targetTab) {
//                     targetTab.classList.add('active');
//                     console.log('Tab activated:', tabId);
//                 }
//             }
            
//             // Show first tab by default if no active tab
//             if (navTabs.length > 0 && tabContents.length > 0) {
//                 const activeTab = document.querySelector('.nav-tab.active');
//                 if (activeTab) {
//                     const href = activeTab.getAttribute('href');
//                     const tabId = href.split('tab=')[1];
//                     if (tabId) {
//                         showTab(tabId);
//                     }
//                 } else {
//                     // No active tab, show first one
//                     const firstTab = navTabs[0];
//                     const href = firstTab.getAttribute('href');
//                     const tabId = href.split('tab=')[1];
//                     if (tabId) {
//                         showTab(tabId);
//                     }
//                 }
//             }
            
//             // Add click event listeners
//             navTabs.forEach(tab => {
//                 tab.addEventListener('click', function(e) {
//                     e.preventDefault();
//                     console.log('=== Tab clicked ===');
//         console.log('Clicked tab:', this.textContent.trim());
//         console.log('Tab href:', this.getAttribute('href'));
        
//         const href = this.getAttribute('href');
//         const tabId = href.split('tab=')[1];
//         console.log('Extracted tabId:', tabId);
        
//         if (tabId) {
//             console.log('Calling showTab with:', tabId);
//             showTab(tabId);
//         } else {
//             console.error('Invalid tab URL:', href);
//         }
//     });
// });
//             // Debug: Log all tab IDs
//             tabContents.forEach(content => {
//                 console.log('Tab content ID:', content.id);
//             });
            
//                     // Fallback: If no tab is shown, show first one
//         setTimeout(() => {
//             const visibleTab = document.querySelector('.tab-content.active');
//             if (!visibleTab) {
//                 console.log('No visible tab found, showing first tab');
//                 if (tabContents.length > 0) {
//                     const firstTab = tabContents[0];
//                     showTab(firstTab.id);
//                 }
//             }
            
//             // Final debug check
//             console.log('=== FINAL TAB STATUS ===');
//             tabContents.forEach(content => {
//                 console.log(`Tab ${content.id}:`, {
//                     active: content.classList.contains('active'),
//                     display: content.style.display,
//                     visible: content.offsetParent !== null
//                 });
//             });
//         }, 100);
//         });
function showTab(tabId) {
    console.log('=== showTab called ===');
    console.log('tabId:', tabId);
    console.log('All tab contents:', document.querySelectorAll('.tab-content'));
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.nav-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    console.log('=== showTab called 1 ===');

    // Add active class to clicked tab
    const activeTab = document.querySelector(`[href="#${tabId}"]`);
    console.log('=== showTab called 2 ===');

    if (activeTab) {
        activeTab.classList.add('active');
        console.log('Active tab set to:', activeTab.textContent.trim());
    }
    
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    console.log('=== showTab called 3 ===');

    tabContents.forEach(content => {
        content.style.display = 'none';
        content.classList.remove('active');
        console.log('Hiding content:', content.id, 'display:', content.style.display);
    });
    
    // Show target tab content
    const targetContent = document.getElementById(tabId);
    console.log('=== showTab called 4 ===');

        if (targetContent) {
        console.log('=== showTab called 5 ===');

        targetContent.classList.add('active');
        targetContent.style.display = 'block';
        console.log('Showing content:', tabId, 'display:', targetContent.style.display);
        console.log('Target content element:', targetContent);
    } else {
        console.log('=== showTab called 6 ===');

        console.error('Target content not found for tabId:', tabId);
    }
}
        
        // New function for changing dynamic buttons
        function showDynamicButtonSettings(buttonName) {
            console.log('showDynamicButtonSettings called with:', buttonName);
            
            // Hide all button settings
            const allSettings = document.querySelectorAll('.dynamic-button-settings');
            console.log('Found settings:', allSettings.length);
            allSettings.forEach(setting => {
                setting.classList.remove('active');
                setting.style.display = 'none';
            });
            
            // Show selected button settings
            const selectedSettings = document.getElementById(`dynamic-settings-${buttonName}`);
            console.log('Selected settings element:', selectedSettings);
            if (selectedSettings) {
                selectedSettings.classList.add('active');
                selectedSettings.style.display = 'block';
            }
            
            // Update active state of button tabs
            const allTabs = document.querySelectorAll('.dynamic-button-tab');
            allTabs.forEach(tab => {
                tab.classList.remove('active');
            });
            
            const activeTab = document.querySelector(`[data-button="${buttonName}"]`);
            if (activeTab) {
                activeTab.classList.add('active');
            }
        }
        

        
        // Function to select sample image
        function selectSampleImage(buttonName) {
    console.log('selectSampleImage called with:', buttonName);

    // Sanitize buttonName to prevent XSS
    const sanitizedButtonName = buttonName.replace(/[^a-zA-Z0-9_-]/g, '');
    const lang = document.currentLang || 'en';

    if (typeof wp !== 'undefined' && wp.media) {
        const mediaUploader = wp.media({
            title: lang === 'fa' ? 'انتخاب عکس نمونه دکمه' : 'Select Button Sample Image',
            button: { text: lang === 'fa' ? 'انتخاب عکس' : 'Select Image' },
            multiple: false,
            library: { type: 'image' }
        });

        // Add CSS only once
        let styleAdded = false;
        mediaUploader.on('ready', function() {
            if (!styleAdded) {
                const style = document.createElement('style');
                style.id = 'qrcdr-modern-media-popup';
                style.textContent = `/* Your CSS styles here */`;
                document.head.appendChild(style);
                styleAdded = true;
            }
        });

        // Clean up CSS on close
        mediaUploader.on('close', function() {
            const style = document.getElementById('qrcdr-modern-media-popup');
            if (style) {
                style.remove();
            }
        });

        // Handle image selection
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            const inputField = document.querySelector(`input[name="dynamic_${sanitizedButtonName}_sample_image"]`);
            const previewDiv = inputField?.closest('.media-upload-field')?.querySelector('.media-upload-preview');

            if (!inputField) {
                console.error(`Input field for dynamic_${sanitizedButtonName}_sample_image not found.`);
                return;
            }
            inputField.value = attachment.url;

            if (previewDiv) {
                previewDiv.innerHTML = `<img src="${attachment.url}" alt="${lang === 'fa' ? 'عکس نمونه دکمه' : 'Button sample image'}" style="max-width: 100px; max-height: 100px;" aria-label="Selected button sample image" />`;
            }
        });

        mediaUploader.open();
    } else {
        // Fallback with URL validation
        const url = prompt(lang === 'fa' ? 'لطفاً URL عکس نمونه را وارد کنید:' : 'Please enter the sample image URL:');
        if (url && /\.(jpg|jpeg|png|gif)$/i.test(url)) {
            const inputField = document.querySelector(`input[name="dynamic_${sanitizedButtonName}_sample_image"]`);
            if (inputField) {
                inputField.value = url;
            } else {
                console.error(`Input field for dynamic_${sanitizedButtonName}_sample_image not found.`);
            }
        } else if (url) {
            alert(lang === 'fa' ? 'لطفاً یک URL معتبر برای تصویر وارد کنید.' : 'Please enter a valid image URL.');
        }
    }
}
        
        // Function to add new static button
        function addNewStaticButton() {
            const buttonName = document.getElementById('new-static-button-name').value.trim();
            
            if (!buttonName) {
                alert(document.currentLang === 'fa' ? 'لطفاً نام دکمه را وارد کنید' : 'Please enter button name');
                return;
            }
            if (buttonName.includes(' ')) {
                alert(document.currentLang === 'fa' ? 'نام دکمه نباید شامل فاصله باشد' : 'Button name should not contain spaces');
                return;
            }
            if (!/^[a-zA-Z_][a-zA-Z0-9_]*$/.test(buttonName)) {
                alert(
                    document.currentLang === 'fa' ? 'نام دکمه باید با حرف یا _ شروع شود و فقط شامل حروف، اعداد و _ باشد' :
                    'Button name must start with a letter or _ and only contain letters, numbers, and _'
                );                
                return;
            }
            
            // Check if button already exists
            const existingButton = document.querySelector(`[data-button="${buttonName}"].static-button-tab`);
            if (existingButton) {
                alert(document.currentLang === 'fa' ? 'این دکمه قبلاً وجود دارد' : 'This button already exists');
                return;
            }
            
            // Create new button tab
            const buttonTabs = document.querySelector('.static-button-selector .button-tabs');
            const newButtonTab = document.createElement('button');
            newButtonTab.type = 'button';
            newButtonTab.className = 'static-button-tab';
            newButtonTab.setAttribute('data-button', buttonName);
            newButtonTab.textContent = buttonName;
            newButtonTab.onclick = () => showStaticButtonSettings(buttonName);
            buttonTabs.appendChild(newButtonTab);
            
            // Clear input
            document.getElementById('new-static-button-name').value = '';
            
            // Show success message
            alert(document.currentLang === 'fa' ? 'دکمه جدید با موفقیت اضافه شد' : 'New button added successfully');
        }

        // Function to show static button settings
        function showStaticButtonSettings(buttonName) {
            // Remove active class from all static button tabs
            document.querySelectorAll('.static-button-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Add active class to clicked tab
            document.querySelector(`[data-button="${buttonName}"].static-button-tab`).classList.add('active');
            
            // Get button title in current language
            const buttonTitles = {
                'link': { fa: 'لینک', en: 'Link' },
                'text': { fa: 'متن', en: 'Text' },
                'email': { fa: 'ایمیل', en: 'Email' },
                'location': { fa: 'مکان', en: 'Location' },
                'wifi': { fa: 'وای‌فای', en: 'WiFi' },
                'vcard': { fa: 'کارت ویزیت', en: 'Business Card' },
                'whatsapp': { fa: 'واتساپ', en: 'WhatsApp' },
                'sms': { fa: 'پیامک', en: 'SMS' },
                'tel': { fa: 'تلفن', en: 'Phone' },
                'skype': { fa: 'اسکایپ', en: 'Skype' },
                'zoom': { fa: 'زوم', en: 'Zoom' },
                'event': { fa: 'رویداد', en: 'Event' },
                'paypal': { fa: 'پی‌پال', en: 'PayPal' },
                'bitcoin': { fa: 'بیت‌کوین', en: 'Bitcoin' },
                'ethereum': { fa: 'اتریوم', en: 'Ethereum' }
            };
            
            const defaultIcons = {
                'link': 'fas fa-link',
                'text': 'fas fa-font',
                'email': 'fas fa-envelope',
                'location': 'fas fa-map-marker-alt',
                'wifi': 'fas fa-wifi',
                'vcard': 'fas fa-address-card',
                'whatsapp': 'fab fa-whatsapp',
                'sms': 'fas fa-sms',
                'tel': 'fas fa-phone',
                'skype': 'fab fa-skype',
                'zoom': 'fas fa-video',
                'event': 'fas fa-calendar-alt',
                'paypal': 'fab fa-paypal',
                'bitcoin': 'fab fa-bitcoin',
                'ethereum': 'fab fa-ethereum'
            };
            
            const currentLang = document.currentLang || 'fa';
            const buttonTitle = buttonTitles[buttonName] || { fa: buttonName, en: buttonName };
            
            // Update settings panel content
            const settingsPanel = document.getElementById('static-button-content');
            settingsPanel.innerHTML = `
                <div style="border: 1px solid #ddd; padding: 15px; border-radius: 6px; background: #f9f9f9;">
                    <h4>${currentLang === 'fa' ? 'تنظیمات دکمه' : 'Button Settings'} ${buttonTitle[currentLang] || buttonTitle.fa}</h4>
                    
                    <div class="form-row">
                        <label>${currentLang === 'fa' ? 'نمایش دکمه در فرانت:' : 'Show Button in Frontend:'}</label>
                        <input type="checkbox" name="static_${buttonName}_visible" value="1" checked />
                        <span style="margin-left: 10px; color: #666;">${currentLang === 'fa' ? 'این دکمه در فرانت‌اند نمایش داده می‌شود' : 'This button will be displayed in frontend'}</span>
                    </div>
                    
                    <div class="form-row">
                        <label>${currentLang === 'fa' ? 'آیکن دکمه:' : 'Button Icon:'}</label>
                        <input type="text" name="static_${buttonName}_icon" value="${defaultIcons[buttonName] || 'fas fa-cog'}" placeholder="${currentLang === 'fa' ? 'نام یا کد آیکن' : 'Icon name or code'}" style="width: 100%;" />
                        <small style="color: #666;">${currentLang === 'fa' ? 'مثال: fas fa-link یا URL تصویر' : 'Example: fas fa-link or image URL'}</small>
                    </div>
                    
                    <div class="form-row">
                        <label>${currentLang === 'fa' ? 'رنگ دکمه:' : 'Button Color:'}</label>
                        <input type="color" name="static_${buttonName}_color" value="#E91E63" style="width: 60px; height: 40px;" />
                    </div>
                    
                    <div class="form-row">
                        <label>${currentLang === 'fa' ? 'عنوان دکمه (فارسی):' : 'Button Title (Persian):'}</label>
                        <input type="text" name="static_${buttonName}_title_fa" value="${buttonTitle.fa}" placeholder="${currentLang === 'fa' ? 'عنوان دکمه' : 'Button title'}" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>${currentLang === 'fa' ? 'عنوان دکمه (انگلیسی):' : 'Button Title (English):'}</label>
                        <input type="text" name="static_${buttonName}_title_en" value="${buttonTitle.en}" placeholder="Button title" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>${currentLang === 'fa' ? 'توضیحات دکمه (فارسی):' : 'Button Description (Persian):'}</label>
                        <textarea name="static_${buttonName}_description_fa" placeholder="${currentLang === 'fa' ? 'توضیحات کامل دکمه' : 'Complete button description'}" style="width: 100%; height: 60px;">ایجاد QR کد برای ${buttonTitle.fa}</textarea>
                    </div>
                    
                    <div class="form-row">
                        <label>${currentLang === 'fa' ? 'توضیحات دکمه (انگلیسی):' : 'Button Description (English):'}</label>
                        <textarea name="static_${buttonName}_description_en" placeholder="Button description" style="width: 100%; height: 60px;">Create QR code for ${buttonTitle.en}</textarea>
                    </div>
                    
                    <div class="form-row" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                        <button type="button" class="button button-secondary delete" onclick="deleteStaticButton('${buttonName}')">
                            ${currentLang === 'fa' ? 'حذف دکمه' : 'Delete Button'} "${buttonTitle[currentLang] || buttonTitle.fa}"
                        </button>
                    </div>
                </div>
            `;
        }

        // Function to delete static button
        function deleteStaticButton(buttonName) {
            if (confirm(document.currentLang === 'fa' ? 'آیا مطمئن هستید که می‌خواهید این دکمه را حذف کنید؟' : 'Are you sure you want to delete this button?')) {
                // Remove button tab
                const buttonTab = document.querySelector(`[data-button="${buttonName}"].static-button-tab`);
                if (buttonTab) {
                    buttonTab.remove();
                }
                
                // Clear settings panel
                document.getElementById('static-button-content').innerHTML = '<div class="loading-message">لطفا یک دکمه انتخاب کنید...</div>';
                
                // Show success message
                alert(document.currentLang === 'fa' ? 'دکمه با موفقیت حذف شد' : 'Button deleted successfully');
            }
        }

        // Function to reset general settings
        function resetGeneralSettings() {
            if (confirm(document.currentLang === 'fa' ? 'آیا مطمئن هستید که می‌خواهید تنظیمات عمومی را به حالت پیش‌فرض بازگردانید؟' : 'Are you sure you want to reset general settings to default?')) {
                // Reset checkboxes
                document.querySelector('input[name="visible_nav_tab"]').checked = true;
                document.querySelector('input[name="visible_dynamic"]').checked = true;
                document.querySelector('input[name="visible_static"]').checked = true;
                
                // Reset dropdowns
                document.querySelector('select[name="selected_tag_title"]').value = 'h1';
                document.querySelector('select[name="selected_tag_description"]').value = 'p';
                
                // Reset colors
                document.querySelector('input[name="orgin_bg_color"]').value = '#ffffff';
                document.querySelector('input[name="menu_bg_color"]').value = '#403199';
                document.querySelector('input[name="active_menu_color"]').value = '#ab9fee';
                document.querySelector('input[name="icon_color"]').value = '#ffffff';
                
                alert(document.currentLang === 'fa' ? 'تنظیمات عمومی بازگردانی شد' : 'General settings reset successfully');
            }
        }

        // Function to reset static tab settings
        function resetStaticSettings() {
            if (confirm(document.currentLang === 'fa' ? 'آیا مطمئن هستید که می‌خواهید تنظیمات استاتیک را به حالت پیش‌فرض بازگردانید؟' : 'Are you sure you want to reset static settings to default?')) {
                location.reload(); // Simple approach - reload page
            }
        }

        // Function to reset dynamic tab settings
        function resetDynamicSettings() {
            if (confirm(document.currentLang === 'fa' ? 'آیا مطمئن هستید که می‌خواهید تنظیمات دینامیک را به حالت پیش‌فرض بازگردانید؟' : 'Are you sure you want to reset dynamic settings to default?')) {
                location.reload(); // Simple approach - reload page
            }
        }

        // Function to reset messages tab settings
        function resetMessagesSettings() {
            if (confirm(document.currentLang === 'fa' ? 'آیا مطمئن هستید که می‌خواهید پیام‌ها را به حالت پیش‌فرض بازگردانید؟' : 'Are you sure you want to reset messages to default?')) {
                location.reload(); // Simple approach - reload page
            }
        }

        // Function to reset all settings
        function resetAllSettings() {
            if (confirm(document.currentLang === 'fa' ? 'آیا مطمئن هستید که می‌خواهید تمام تنظیمات را به حالت پیش‌فرض بازگردانید؟ این عمل غیرقابل بازگشت است!' : 'Are you sure you want to reset ALL settings to default? This action cannot be undone!')) {
                if (confirm(document.currentLang === 'fa' ? 'تأیید نهایی: تمام داده‌های سفارشی شما حذف خواهد شد!' : 'Final confirmation: All your custom data will be deleted!')) {
                    // Send AJAX request to reset all settings
                    const formData = new FormData();
                    formData.append('action', 'qrcdr_reset_all_settings');
                    formData.append('nonce', '<?php echo wp_create_nonce('qrcdr_reset_nonce'); ?>');
                    
                    fetch(ajaxurl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(document.currentLang === 'fa' ? 'تمام تنظیمات بازگردانی شد' : 'All settings reset successfully');
                            location.reload();
                        } else {
                            alert(document.currentLang === 'fa' ? 'خطا در بازگردانی تنظیمات' : 'Error resetting settings');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(document.currentLang === 'fa' ? 'خطا در ارتباط با سرور' : 'Error communicating with server');
                    });
                }
            }
        }

        // Function to add new dynamic button
        function addNewDynamicButton() {
            const buttonName = document.getElementById('new-button-name').value.trim();
            
            if (!buttonName) {
                alert(document.currentLang === 'fa' ? 'لطفاً نام دکمه را وارد کنید' : 'Please enter button name');
                return;
            }
            if (buttonName.includes(' ')) {
        alert(document.currentLang === 'fa' ? 'نام دکمه نباید شامل فاصله باشد' : 'Button name should not contain spaces');
        return;
    }
            if (!/^[a-zA-Z_][a-zA-Z0-9_]*$/.test(buttonName)) {
                alert(
                    document.currentLang === 'fa' ? 'نام دکمه باید با حرف یا _ شروع شود و فقط شامل حروف، اعداد و _ باشد' :
                    document.currentLang === 'es' ? 'El nombre del botón debe comenzar con una letra o _ y solo contener letras, números y _' :
                    document.currentLang === 'fr' ? 'Le nom du bouton doit commencer par une lettre ou _ et ne contenir que des lettres, des chiffres et _' :
                    'Button name must start with a letter or _ and only contain letters, numbers, and _' // Default (English)
                    );                
                    return;
            }
            
            // Check if button already exists
            const existingButton = document.querySelector(`[onclick="showDynamicButtonSettings('${buttonName}')"]`);
            if (existingButton) {
                alert(
                document.currentLang === 'fa' ? 'این دکمه قبلاً وجود دارد' :
                document.currentLang === 'es' ? 'Este botón ya existe' :
                document.currentLang === 'fr' ? 'Ce bouton existe déjà' :
                'This button already exists' // Default (English)
                );                
                return;
            }
            
            // Create new button tab
            const buttonTabs = document.querySelector('.button-tabs');
            const newButtonTab = document.createElement('button');
            newButtonTab.type = 'button';
            newButtonTab.className = 'dynamic-button-tab';
            newButtonTab.setAttribute('data-button', buttonName);
            newButtonTab.textContent = buttonName;
            newButtonTab.onclick = () => showDynamicButton(buttonName);
            buttonTabs.appendChild(newButtonTab);
                // Create settings container for new button
            createDynamicButtonSettings(buttonName);
            
            // Clear input
            document.getElementById('new-button-name').value = '';
            
            // Create new settings container
            const settingsContainer = document.querySelector('.dynamic-button-settings').parentNode;
            const newSettings = document.createElement('div');
            newSettings.id = `dynamic-settings-${buttonName}`;
            newSettings.className = 'dynamic-button-settings';
            newSettings.style.cssText = 'border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 6px; background: #f9f9f9; display: none;';
            
            // Add basic content for new button
            newSettings.innerHTML = `
                <h3>${buttonName}</h3>
                <div class="form-row">
                    <label>نمایش دکمه:</label>
                    <input type="checkbox" name="dynamic_${buttonName}_visible" value="1" checked />
                    <span style="margin-left: 10px; color: #666;">این دکمه در فرانت‌اند نمایش داده می‌شود</span>
                </div>
                <div class="language-tabs">
                    <h4>متن‌های چندزبانه</h4>
                    <div style="margin-bottom: 15px;">
                        <button type="button" class="lang-tab-btn active" onclick="showDynamicLanguageTab('${buttonName}', 'fa',event)">فارسی</button>
                        <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('${buttonName}', 'en', event)">English</button>
                        <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('${buttonName}', 'ar', event)">العربية</button>
                        <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('${buttonName}', 'tr', event)">Türkçe</button>
                        <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('${buttonName}', 'ru', event)">Русский</button>
                        <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('${buttonName}', 'fr', event)">Français</button>
                        <button type="button" class="lang-tab-btn" onclick="showDynamicLanguageTab('${buttonName}', 'de', event)">Deutsch</button>
                    </div>
                </div>
                <div id="dynamic-${buttonName}-fa" class="dynamic-lang-content active">
                    <h5>فارسی</h5>
                    <div class="form-row">
                        <label>عنوان دکمه:</label>
                        <input type="text" name="dynamic_${buttonName}_title_fa" value="${buttonName}" placeholder="عنوان دکمه" style="width: 100%;" />
                    </div>
                    <div class="form-row">
                        <label>توضیحات دکمه:</label>
                        <textarea name="dynamic_${buttonName}_description_fa" placeholder="توضیحات کامل دکمه" style="width: 100%; height: 60px;"></textarea>
                    </div>
                    <div class="form-row">
                        <label>زیر متن دکمه:</label>
                        <input type="text" name="dynamic_${buttonName}_subtext_fa" placeholder="زیر متن دکمه" style="width: 100%;" />
                    </div>
                </div>
                <div id="dynamic-${buttonName}-en" class="dynamic-lang-content" style="display: none;">
                    <h5>English</h5>
                    <div class="form-row">
                        <label>Button Title:</label>
                        <input type="text" name="dynamic_${buttonName}_title_en" value="${buttonName}" placeholder="Button title" style="width: 100%;" />
                    </div>
                    <div class="form-row">
                        <label>Button Description:</label>
                        <textarea name="dynamic_${buttonName}_description_en" placeholder="Complete button description" style="width: 100%; height: 60px;"></textarea>
                    </div>
                    <div class="form-row">
                        <label>Button Subtext:</label>
                        <input type="text" name="dynamic_${buttonName}_subtext_en" placeholder="Button subtext" style="width: 100%;" />
                    </div>
                </div>
                <div id="dynamic-${buttonName}-ar" class="dynamic-lang-content" style="display: none;">
                    <h5>العربية</h5>
                    <div class="form-row">
                        <label>عنوان الزر:</label>
                        <input type="text" name="dynamic_${buttonName}_title_ar" value="${buttonName}" placeholder="عنوان الزر" style="width: 100%;" />
                    </div>
                    <div class="form-row">
                        <label>وصف الزر:</label>
                        <textarea name="dynamic_${buttonName}_description_ar" placeholder="وصف كامل للزر" style="width: 100%; height: 60px;"></textarea>
                    </div>
                    <div class="form-row">
                        <label>النص الفرعي للزر:</label>
                        <input type="text" name="dynamic_${buttonName}_subtext_ar" placeholder="النص الفرعي للزر" style="width: 100%;" />
                    </div>
                </div>
                <div id="dynamic-${buttonName}-tr" class="dynamic-lang-content" style="display: none;">
                    <h5>Türkçe</h5>
                    <div class="form-row">
                        <label>Düğme Başlığı:</label>
                        <input type="text" name="dynamic_${buttonName}_title_tr" value="${buttonName}" placeholder="Düğme başlığı" style="width: 100%;" />
                    </div>
                    <div class="form-row">
                        <label>Düğme Açıklaması:</label>
                        <textarea name="dynamic_${buttonName}_description_tr" placeholder="Tam düğme açıklaması" style="width: 100%; height: 60px;"></textarea>
                    </div>
                    <div class="form-row">
                        <label>Düğme Alt Metni:</label>
                        <input type="text" name="dynamic_${buttonName}_subtext_tr" placeholder="Düğme alt metni" style="width: 100%;" />
                    </div>
                </div>
                <div id="dynamic-${buttonName}-ru" class="dynamic-lang-content" style="display: none;">
                    <h5>Русский</h5>
                    <div class="form-row">
                        <label>Заголовок кнопки:</label>
                        <input type="text" name="dynamic_${buttonName}_title_ru" value="${buttonName}" placeholder="Заголовок кнопки" style="width: 100%;" />
                    </div>
                    <div class="form-row">
                        <label>Описание кнопки:</label>
                        <textarea name="dynamic_${buttonName}_description_ru" placeholder="Полное описание кнопки" style="width: 100%; height: 60px;"></textarea>
                    </div>
                    <div class="form-row">
                        <label>Подтекст кнопки:</label>
                        <input type="text" name="dynamic_${buttonName}_subtext_ru" placeholder="Подтекст кнопки" style="width: 100%;" />
                    </div>
                </div>
                <div id="dynamic-${buttonName}-fr" class="dynamic-lang-content" style="display: none;">
                    <h5>Français</h5>
                    <div class="form-row">
                        <label>Titre du bouton:</label>
                        <input type="text" name="dynamic_${buttonName}_title_fr" value="${buttonName}" placeholder="Titre du bouton" style="width: 100%;" />
                    </div>
                    <div class="form-row">
                        <label>Description du bouton:</label>
                        <textarea name="dynamic_${buttonName}_description_fr" placeholder="Description complète du bouton" style="width: 100%; height: 60px;"></textarea>
                    </div>
                    <div class="form-row">
                        <label>Sous-texte du bouton:</label>
                        <input type="text" name="dynamic_${buttonName}_subtext_fr" placeholder="Sous-texte du bouton" style="width: 100%;" />
                    </div>
                </div>
                <div id="dynamic-${buttonName}-de" class="dynamic-lang-content" style="display: none;">
                    <h5>Deutsch</h5>
                    <div class="form-row">
                        <label>Schaltflächentitel:</label>
                        <input type="text" name="dynamic_${buttonName}_title_de" value="${buttonName}" placeholder="Schaltflächentitel" style="width: 100%;" />
                    </div>
                    <div class="form-row">
                        <label>Schaltflächenbeschreibung:</label>
                        <textarea name="dynamic_${buttonName}_description_de" placeholder="Vollständige Schaltflächenbeschreibung" style="width: 100%; height: 60px;"></textarea>
                    </div>
                    <div class="form-row">
                        <label>Schaltflächenuntertext:</label>
                        <input type="text" name="dynamic_${buttonName}_subtext_de" placeholder="Schaltflächenuntertext" style="width: 100%;" />
                    </div>
                </div>
                
                <!-- New fields for each dynamic button -->
                <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px;">
                    <h4 style="margin-top: 0; color: #856404;">تنظیمات اضافی دکمه</h4>
                    
                    <div class="form-row">
                        <label>آیکن دکمه (Font Awesome):</label>
                        <div class="media-upload-field">
                            <input type="text" name="dynamic_${buttonName}_icon_image" value="" placeholder="مثال: fas fa-gem" style="flex: 1;" />
                            <button type="button" class="media-upload-button" onclick="selectIconImage('${buttonName}')">انتخاب آیکن</button>
                            <div class="media-upload-preview"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <label>رنگ دکمه:</label>
                        <div class="color-picker-field">
                            <input type="color" name="dynamic_${buttonName}_button_color" value="#e9354d" class="color-picker-input" />
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <label>لینک دکمه:</label>
                        <input type="url" name="dynamic_${buttonName}_link" value="" placeholder="https://example.com" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>متن دکمه ساخت:</label>
                        <input type="text" name="dynamic_${buttonName}_create_text" value="ساخت کیوآر" placeholder="متن دکمه ساخت" style="width: 100%;" />
                    </div>
                    
                    <div class="form-row">
                        <label>متن دکمه بازدید:</label>
                        <input type="text" name="dynamic_${buttonName}_visit_text" value="بازدید از سایت" placeholder="متن دکمه بازدید" style="width: 100%;" />
                    </div>
                    
                    <!-- فیلد عکس نمونه -->
                    <div class="form-row">
                        <label>عکس نمونه دکمه:</label>
                        <div class="media-upload-field">
                            <input type="text" name="dynamic_${buttonName}_sample_image" value="" placeholder="URL عکس نمونه یا انتخاب از گالری" style="width: 100%;" />
                            <button type="button" class="media-upload-button" onclick="selectSampleImage('${buttonName}')">انتخاب عکس</button>
                        </div>
                        <div class="media-upload-preview" style="margin-top: 10px;"></div>
                    </div>
                </div>
                
                <!-- دکمه حذف دکمه دینامیک -->
                <div class="form-row" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <button type="button" class="button button-secondary delete" onclick="deleteDynamicButton('${buttonName}')">
                        حذف دکمه "${buttonName}"
                    </button>
                </div>
            `;
            
            settingsContainer.appendChild(newSettings);
            
            // Clear input
            document.getElementById('new-button-name').value = '';
            
            // Show success message
            alert(document.currentLang === 'fa' ? `دکمه "${buttonName}" با موفقیت اضافه شد` : `Button "${buttonName}" added successfully`);
            
            // Show the new button settings
            showDynamicButtonSettings(buttonName);
        }
        function createDynamicButtonSettings(buttonName) {
    const container = document.querySelector('.dynamic-button-settings').parentNode;
    
    const newSettings = document.createElement('div');
    newSettings.id = `dynamic-settings-${buttonName}`;
    newSettings.className = 'dynamic-button-settings';
    newSettings.style.display = 'none';
    newSettings.style.border = '1px solid #ddd';
    newSettings.style.padding = '15px';
    newSettings.style.margin = '10px 0';
    newSettings.style.borderRadius = '6px';
    newSettings.style.background = '#f9f9f9';
    
    // Add content for new button
    newSettings.innerHTML = `
        <h3>${buttonName}</h3>
        <div class="form-row">
            <label>نمایش دکمه:</label>
            <input type="checkbox" name="dynamic_${buttonName}_visible" value="1" checked />
        </div>
        <!-- Add more fields as needed -->
    `;
    
    container.appendChild(newSettings);
}
        // Function to delete dynamic button
        function deleteDynamicButton(buttonName) {
            if (confirm(`آیا مطمئن هستید که می‌خواهید دکمه "${buttonName}" را حذف کنید؟`)) {
                // Remove button tab
                const buttonTab = document.querySelector(`[data-button="${buttonName}"]`);
                if (buttonTab) {
                    buttonTab.remove();
                }
                
                // Remove settings container
                const settingsContainer = document.getElementById(`dynamic-settings-${buttonName}`);
                if (settingsContainer) {
                    settingsContainer.remove();
                }
                
                // Show first available button settings
                const firstButton = document.querySelector('.dynamic-button-tab');
                if (firstButton) {
                    const buttonName = firstButton.getAttribute('data-button');
                    if (buttonName) {
                        showDynamicButtonSettings(buttonName);
                    }
                }
                
                alert(`دکمه "${buttonName}" با موفقیت حذف شد!`);
            }
        }
        
        // Add event listener for color pickers
        document.addEventListener('DOMContentLoaded', function() {

    console.log('=== DOM Content Loaded ===');
    console.log('All nav tabs found:', document.querySelectorAll('.nav-tab').length);
    console.log('All tab contents found:', document.querySelectorAll('.tab-content').length);    
            // Add event listener for dynamic button tabs
            const dynamicButtonTabs = document.querySelectorAll('.dynamic-button-tab');
            dynamicButtonTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const buttonName = this.getAttribute('data-button');
                    if (buttonName) {
                        showDynamicButtonSettings(buttonName);
                        
                        // Update active state
                        dynamicButtonTabs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });

            // Add event listener for language tabs
            const languageTabs = document.querySelectorAll('.lang-tab-btn');
            languageTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const buttonName = this.closest('.dynamic-button-settings')?.getAttribute('data-button');
                    const lang = this.getAttribute('data-lang');
                    if (buttonName && lang) {
                        showDynamicLanguageTab(buttonName, lang, event);
                    }
                });
            });
        });
    </script>
<?php
}

function qrcdr_generate_dynamic_css(): string
{
    // Get all appearance settings
    $main_font_family = get_option('qrcdr_main_font_family', 'Vazir');
    $custom_font_url = get_option('qrcdr_custom_font_url', '');
    $background_type = get_option('qrcdr_background_type', 'color');
    $background_image = get_option('qrcdr_background_image', '');
    $background_transparency = get_option('qrcdr_background_transparency', '0');
    
    $orgin_bg_color = get_option('qrcdr_orgin_bg_color', '#ffffff');
    $menu_bg_color = get_option('qrcdr_menu_bg_color', '#403199');
    $active_menu_color = get_option('qrcdr_active_menu_color', '#ab9fee');
    $icon_color = get_option('qrcdr_icon_color', '#ffffff');
    $top_bg_color = get_option('qrcdr_top_bg_color', '#fbe0e3');
    $tab_bg_color = get_option('qrcdr_tab_bg_color', '#ffffff');
    $active_tab_color = get_option('qrcdr_active_tab_color', '#403199');
    $button_color = get_option('qrcdr_button_color', '#e9354d');
    $icon_bg_color = get_option('qrcdr_icon_bg_color', '#ff4d4d');
    $bottom_line_color = get_option('qrcdr_bottom_line_color', '#403199');
    
    $tab_size = get_option('qrcdr_tab_size', '50%');
    $text_size = get_option('qrcdr_text_size', '12px');
    $tab_text_size = get_option('qrcdr_tab_text_size', '16px');
    $tab_text_active_size = get_option('qrcdr_tab_text_active_size', '17px');
    $button_size = get_option('qrcdr_button_size', '100px');
    $icon_size = get_option('qrcdr_icon_size', '12px');
    
    // Generate CSS
    $css = "/* QRcdr Dynamic Styles - Generated automatically */\n";
    
    // Custom font import
    if ($main_font_family === 'custom' && $custom_font_url) {
        $css .= "@import url('{$custom_font_url}');\n";
    }
    
    // CSS Variables
    $css .= ":root {\n";
    $css .= "    --qrcdr-main-font: '{$main_font_family}', 'Tahoma', sans-serif;\n";
    $css .= "    --qrcdr-orgin-bg-color: {$orgin_bg_color};\n";
    $css .= "    --qrcdr-menu-bg-color: {$menu_bg_color};\n";
    $css .= "    --qrcdr-active-menu-color: {$active_menu_color};\n";
    $css .= "    --qrcdr-icon-color: {$icon_color};\n";
    $css .= "    --qrcdr-top-bg-color: {$top_bg_color};\n";
    $css .= "    --qrcdr-tab-bg-color: {$tab_bg_color};\n";
    $css .= "    --qrcdr-active-tab-color: {$active_tab_color};\n";
    $css .= "    --qrcdr-button-color: {$button_color};\n";
    $css .= "    --qrcdr-icon-bg-color: {$icon_bg_color};\n";
    $css .= "    --qrcdr-bottom-line-color: {$bottom_line_color};\n";
    $css .= "    --qrcdr-tab-size: {$tab_size};\n";
    $css .= "    --qrcdr-text-size: {$text_size};\n";
    $css .= "    --qrcdr-tab-text-size: {$tab_text_size};\n";
    $css .= "    --qrcdr-tab-text-active-size: {$tab_text_active_size};\n";
    $css .= "    --qrcdr-button-size: {$button_size};\n";
    $css .= "    --qrcdr-icon-size: {$icon_size};\n";
    $css .= "}\n\n";
    
    // Font family application
    $css .= ".qrcdr-container, .qrcdr-container * {\n";
    $css .= "    font-family: var(--qrcdr-main-font);\n";
    $css .= "}\n\n";
    
    // Background styles based on type
    switch ($background_type) {
        case 'image':
            if ($background_image) {
                $css .= ".qrcdr-container {\n";
                $css .= "    background-image: url('{$background_image}');\n";
                $css .= "    background-size: cover;\n";
                $css .= "    background-position: center;\n";
                $css .= "    background-repeat: no-repeat;\n";
                if ($background_transparency > 0) {
                    $css .= "    background-color: rgba(255, 255, 255, " . (1 - $background_transparency / 100) . ");\n";
                    $css .= "    background-blend-mode: overlay;\n";
                }
                $css .= "}\n\n";
            }
            break;
            
        case 'transparent':
            $css .= ".qrcdr-container {\n";
            $css .= "    background: rgba(255, 255, 255, " . (1 - $background_transparency / 100) . ");\n";
            $css .= "}\n\n";
            break;
            
        case 'gradient':
            $gradient_color_1 = get_option('qrcdr_gradient_color_1', '#ffffff');
            $gradient_color_2 = get_option('qrcdr_gradient_color_2', '#403199');
            $gradient_direction = get_option('qrcdr_gradient_direction', '135deg');
            
            $css .= ".qrcdr-container {\n";
            $css .= "    background: linear-gradient({$gradient_direction}, {$gradient_color_1}, {$gradient_color_2});\n";
            if ($background_transparency > 0) {
                $css .= "    opacity: " . (1 - $background_transparency / 100) . ";\n";
            }
            $css .= "}\n\n";
            break;
            
        default: // color
            $css .= ".qrcdr-container {\n";
            $css .= "    background: var(--qrcdr-orgin-bg-color);\n";
            $css .= "}\n\n";
            break;
    }
    
    return $css;
}

function qrcdr_enqueue_dynamic_styles(): void
{
    // Generate and enqueue dynamic CSS
    $css = qrcdr_generate_dynamic_css();
    wp_add_inline_style('qrcdr-frontend', $css);
}
add_action('wp_enqueue_scripts', 'qrcdr_enqueue_dynamic_styles');

function qrcdr_admins_menu_style(): void
{
    ?>
    <style scoped>
        .qrcdr-admin-page {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 20px;
        }
        
        .qrcdr-admin-page h1 {
            color: #23282d;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .qrcdr-tabs {
            border-bottom: 2px solid #ddd;
            margin-bottom: 30px;
        }
        
        .qrcdr-tab {
            display: inline-block;
            padding: 12px 24px;
            background: #f1f1f1;
            border: none;
            cursor: pointer;
            margin-right: 5px;
            border-radius: 6px 6px 0 0;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .qrcdr-tab:hover {
            background: #e1e1e1;
        }
        
        .qrcdr-tab.active {
            background: #0073aa;
            color: white;
            transform: translateY(-2px);
        }
        
        .qrcdr-tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .qrcdr-tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .qrcdr-section {
            background: #f9f9f9;
            padding: 25px;
            margin: 20px 0;
            border-radius: 10px;
            border-left: 4px solid #0073aa;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .qrcdr-section h3 {
            margin-top: 0;
            color: #0073aa;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        
        .qrcdr-form-row {
            display: flex;
            align-items: center;
            margin: 15px 0;
            gap: 15px;
        }
        
        .qrcdr-form-row label {
            min-width: 150px;
            font-weight: 500;
        }
        
        .qrcdr-color-picker {
            width: 60px;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .qrcdr-color-picker:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .color-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        
        .color-preview {
            width: 60px;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .color-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .color-code {
            font-family: monospace;
            font-size: 12px;
            color: #666;
            min-width: 70px;
        }
        
        .qrcdr-gradient-preview {
            width: 250px;
            height: 80px;
            border: 3px solid #ddd;
            border-radius: 10px;
            margin: 15px 0;
            transition: all 0.3s ease;
        }
        
        .qrcdr-gradient-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .qrcdr-font-selector {
            width: 250px;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .qrcdr-font-selector:focus {
            border-color: #0073aa;
            box-shadow: 0 0 0 3px rgba(0,115,170,0.1);
            outline: none;
        }
        
        .qrcdr-language-tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .qrcdr-language-tab {
            padding: 12px 20px;
            background: #f1f1f1;
            border: none;
            cursor: pointer;
            margin-right: 8px;
            margin-bottom: 8px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            min-width: 80px;
            text-align: center;
        }
        
        .qrcdr-language-tab:hover {
            background: #e1e1e1;
            transform: translateY(-2px);
        }
        
        .qrcdr-language-tab.active {
            background: #0073aa;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,115,170,0.3);
        }
        
        .qrcdr-language-content {
            display: none;
            animation: slideDown 0.3s ease;
        }
        
        .qrcdr-language-content.active {
            display: block;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .qrcdr-transparency-option {
            margin: 15px 0;
            padding: 15px;
            background: #f0f8ff;
            border-radius: 6px;
            border: 1px solid #b3d9ff;
        }
        
        .qrcdr-transparency-option input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }
        
        .qrcdr-image-upload {
            margin: 15px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #ddd;
            text-align: center;
        }
        
        .qrcdr-image-preview {
            max-width: 250px;
            max-height: 180px;
            margin: 15px auto;
            border: 3px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .qrcdr-submit-btn {
            background: #0073aa;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .qrcdr-submit-btn:hover {
            background: #005a87;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,115,170,0.3);
        }
        
        .qrcdr-notice {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        
        .qrcdr-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
    </style>
    <?php
}
add_action('admin_head', 'qrcdr_admins_menu_style');

// Add JavaScript for admin functionality
function qrcdr_admin_scripts(): void
{
    // Get current language for JavaScript
    $current_lang = qrcdr_get_wp_language();
    ?>
    <script>
        document.currentLang = '<?php echo $current_lang; ?>';
        jQuery(document).ready(function($) {
            // Tab switching
            $('.qrcdr-tab').click(function() {
                $('.qrcdr-tab').removeClass('active');
                $('.qrcdr-tab-content').removeClass('active').hide();
                
                $(this).addClass('active');
                var tabId = $(this).data('tab');
                $('#' + tabId).addClass('active').show();
                
                console.log('Tab clicked:', tabId);
            });
            
            // Language tab switching
            $('.qrcdr-language-tab').click(function() {
                $('.qrcdr-language-tab').removeClass('active');
                $('.qrcdr-language-content').removeClass('active');
                
                $(this).addClass('active');
                var lang = $(this).data('lang');
                $('.qrcdr-language-content[data-lang="' + lang + '"]').addClass('active');
            });
            
            // Color picker change - update preview immediately
            $('.qrcdr-color-picker').on('input change', function() {
                var color = $(this).val();
                var previewId = $(this).data('preview');
                
                if (previewId) {
                    $('#' + previewId).css('background-color', color);
                }
                
                updateGradientPreview();
            });
            
            // Background type change
            $('input[name="background_type"]').change(function() {
                var type = $(this).val();
                
                // Hide all options first
                $('.qrcdr-gradient-options, .qrcdr-transparency-options, .qrcdr-image-options').hide();
                
                if (type === 'gradient') {
                    $('.qrcdr-gradient-options').show();
                    updateGradientPreview();
                } else if (type === 'transparent') {
                    $('.qrcdr-transparency-options').show();
                } else if (type === 'image') {
                    $('.qrcdr-image-options').show();
                }
            });
            
            // Update gradient preview
            function updateGradientPreview() {
                var color1 = $('input[name="gradient_color_1"]').val();
                var color2 = $('input[name="gradient_color_2"]').val();
                var direction = $('select[name="gradient_direction"]').val();
                
                if (color1 && color2) {
                    $('.qrcdr-gradient-preview').css('background', 'linear-gradient(' + direction + ', ' + color1 + ', ' + color2 + ')');
                }
            }
            
            // File selector for background image
            $('.qrcdr-image-selector').click(function(e) {
                e.preventDefault();
                
                var frame = wp.media({
                    title: 'Select Background Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });
                
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('input[name="background_image"]').val(attachment.url);
                    $('.qrcdr-image-preview').attr('src', attachment.url).show();
                });
                
                frame.open();
            });
            
            // Initialize
            updateGradientPreview();
            
            // Show initial background type options
            var initialType = $('input[name="background_type"]:checked').val();
            if (initialType) {
                $('input[name="background_type"][value="' + initialType + '"]').trigger('change');
            }
        });
    </script>
    <?php
}
add_action('admin_footer', 'qrcdr_admin_scripts');

// Enqueue WordPress media scripts
function qrcdr_enqueue_media(): void
{
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'qrcdr_enqueue_media');

// Frontend integration already included at the top of the file

// Add AJAX actions for real-time QR preview
add_action('wp_ajax_qrcdr_preview', 'qrcdr_preview_qr');
add_action('wp_ajax_nopriv_qrcdr_preview', 'qrcdr_preview_qr');

function qrcdr_preview_qr(): void
{
   
      // Verify nonce
      if (!wp_verify_nonce($_POST['nonce'] ?? '', 'qrcdr_preview_nonce')) {
        wp_die('Security check failed');
    }

    try {
        $qr_data = [
            'text' => sanitize_text_field($_POST['text'] ?? ''),
            'type' => sanitize_text_field($_POST['type'] ?? 'text'),
            'size' => intval($_POST['size'] ?? 200),
            'level' => sanitize_text_field($_POST['level'] ?? 'M'),
            'foreground' => sanitize_hex_color($_POST['foreground'] ?? '#000000'),
            'background' => sanitize_hex_color($_POST['background'] ?? '#FFFFFF'),
            'pattern' => sanitize_text_field($_POST['pattern'] ?? 'square'),
            'markerOut' => sanitize_text_field($_POST['markerOut'] ?? 'square'),
            'markerIn' => sanitize_text_field($_POST['markerIn'] ?? 'square'),
            'logo' => $_POST['logo'] ?? null,
            'frame' => $_POST['frame'] ?? null,
            'eyeStyle' => sanitize_text_field($_POST['eyeStyle'] ?? 'square'),
            'border' => intval($_POST['border'] ?? 0)
        ];

        // Validate required fields
        if (empty($qr_data['text'])) {
            throw new Exception('Text is required');
        }

        // Generate QR preview using enhanced library
        $qr_preview = qrcdr_generate_enhanced_qr_preview($qr_data);
        
        wp_send_json_success([
            'preview' => $qr_preview,
            'data' => $qr_data
        ]);

    } catch (Exception $e) {
        wp_send_json_error([
            'message' => $e->getMessage()
        ]);
    }
}

// Add AJAX action for final QR generation and download
add_action('wp_ajax_qrcdr_generate', 'qrcdr_generate_final_qr');
add_action('wp_ajax_nopriv_qrcdr_generate', 'qrcdr_generate_final_qr');
// Enhanced QR preview generation
function qrcdr_generate_enhanced_qr_preview(array $qr_data): string
{
    // Include enhanced QR generation library
    require_once plugin_dir_path(__FILE__) . 'lib/class-qrcdr.php';
    
    // Convert hex colors to decimal
    $foreground_dec = hexdec(ltrim($qr_data['foreground'], '#'));
    $background_dec = hexdec(ltrim($qr_data['background'], '#'));
    
    // Generate SVG QR code with enhanced styling
    $svg = QRcdr::svg(
        $qr_data['text'], 
        false, 
        $qr_data['level'], 
        $qr_data['size']/25, 
        4, 
        false, 
        $background_dec, 
        $foreground_dec, 
        false
    );
    
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}
function qrcdr_generate_final_qr(): void
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'qrcdr_generate_nonce')) {
        wp_die('Security check failed');
    }

    try {
        $qr_data = [
            'text' => sanitize_text_field($_POST['text'] ?? ''),
            'type' => sanitize_text_field($_POST['type'] ?? 'text'),
            'size' => intval($_POST['size'] ?? 200),
            'level' => sanitize_text_field($_POST['level'] ?? 'M'),
            'foreground' => sanitize_hex_color($_POST['foreground'] ?? '#000000'),
            'background' => sanitize_hex_color($_POST['background'] ?? '#FFFFFF'),
            'pattern' => sanitize_text_field($_POST['pattern'] ?? 'square'),
            'markerOut' => sanitize_text_field($_POST['markerOut'] ?? 'square'),
            'markerIn' => sanitize_text_field($_POST['markerIn'] ?? 'square'),
            'logo' => $_POST['logo'] ?? null,
            'frame' => $_POST['frame'] ?? null,
            'format' => sanitize_text_field($_POST['format'] ?? 'PNG')
        ];

        // Validate required fields
        if (empty($qr_data['text'])) {
            throw new Exception('Text is required');
        }

        // Generate final QR
        $qr_file = qrcdr_generate_final_qr_file($qr_data);
        
        wp_send_json_success([
            'file_url' => $qr_file['url'],
            'file_path' => $qr_file['path'],
            'filename' => $qr_file['filename']
        ]);

    } catch (Exception $e) {
        wp_send_json_error([
            'message' => $e->getMessage()
        ]);
    }
}

// Function to generate QR preview
function qrcdr_generate_qr_preview(array $qr_data): string
{
    // Include QR generation library
    require_once plugin_dir_path(__FILE__) . 'lib/phpqrcode.php';
    
    // Create temporary file for preview
    $temp_file = wp_tempnam('qrcdr_preview_');
    
    // Generate QR code
    $qr = new QRcode();
    $qr->png($qr_data['text'], $temp_file, $qr_data['level'], $qr_data['size'], 2);
    
    // Convert to base64 for preview
    $image_data = file_get_contents($temp_file);
    $base64 = base64_encode($image_data);
    
    // Clean up temp file
    unlink($temp_file);
    
    return 'data:image/png;base64,' . $base64;
}

// Function to generate final QR file
function qrcdr_generate_final_qr_file(array $qr_data): array
{
    // Include QR generation library
    require_once plugin_dir_path(__FILE__) . 'lib/phpqrcode.php';
    
    // Create uploads directory if it doesn't exist
    $upload_dir = wp_upload_dir();
    $qr_dir = $upload_dir['basedir'] . '/qrcdr/';
    
    if (!is_dir($qr_dir)) {
        wp_mkdir_p($qr_dir);
    }
    
    // Generate unique filename
    $filename = 'qrcdr_' . uniqid() . '.' . strtolower($qr_data['format']);
    $file_path = $qr_dir . $filename;
    
    // Generate QR code
    $qr = new QRcode();
    
    if (strtoupper($qr_data['format']) === 'PNG') {
        $qr->png($qr_data['text'], $file_path, $qr_data['level'], $qr_data['size'], 2);
    } elseif (strtoupper($qr_data['format']) === 'SVG') {
        // For SVG, we'll use a different approach
        $svg_content = qrcdr_generate_svg_qr($qr_data);
        file_put_contents($file_path, $svg_content);
    }
    
    return [
        'url' => $upload_dir['baseurl'] . '/qrcdr/' . $filename,
        'path' => $file_path,
        'filename' => $filename
    ];
}

// Function to generate SVG QR code
function qrcdr_generate_svg_qr(array $qr_data): string
{
    // This is a simplified SVG generation
    // In a real implementation, you'd use a proper SVG QR library
    
    $svg = '<?xml version="1.0" encoding="UTF-8"?>';
    $svg .= '<svg width="' . $qr_data['size'] . '" height="' . $qr_data['size'] . '" xmlns="http://www.w3.org/2000/svg">';
    $svg .= '<rect width="100%" height="100%" fill="' . $qr_data['background'] . '"/>';
    $svg .= '<text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="' . $qr_data['foreground'] . '">';
    $svg .= htmlspecialchars($qr_data['text']);
    $svg .= '</text>';
    $svg .= '</svg>';
    
    return $svg;
}

// Add AJAX handler for reset all settings
function qrcdr_handle_reset_all_settings() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'qrcdr_reset_nonce')) {
        wp_die(json_encode(['success' => false, 'message' => 'Invalid nonce']));
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_die(json_encode(['success' => false, 'message' => 'Insufficient permissions']));
    }
    
    // Get all options with qrcdr prefix
    global $wpdb;
    $options = $wpdb->get_results("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'qrcdr_%'");
    
    // Delete all qrcdr options
    foreach ($options as $option) {
        delete_option($option->option_name);
    }
    
    // Return success response
    wp_die(json_encode(['success' => true, 'message' => 'All settings reset successfully']));
}
add_action('wp_ajax_qrcdr_reset_all_settings', 'qrcdr_handle_reset_all_settings');

// Add debug logging
if (defined('WP_DEBUG') && WP_DEBUG) {
    add_action('wp_footer', function() {
        if (has_shortcode(get_the_content(), 'qrcdr')) {
            echo '<!-- QRcdr Debug: Shortcode detected on this page -->';
            echo '<!-- QRcdr Debug: Plugin URL: ' . plugin_dir_url(__FILE__) . ' -->';
            echo '<!-- QRcdr Debug: Assets URL: ' . plugin_dir_url(__FILE__) . 'frontend/dist/ -->';
        }
    });
}

// CSS for button settings
?>
<style type="text/css">
/* Button List Styling */
.button-list-container {
    margin: 20px 0;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #e1e1e1;
}

.button-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.static-button-tab {
    padding: 10px 20px;
    background: #ffffff;
    border: 2px solid #e1e1e1;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    min-width: 120px;
}

.static-button-tab:hover {
    background: #f0f8ff;
    border-color: #b3d9ff;
    color: #0066cc;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 102, 204, 0.2);
}

.static-button-tab.active {
    background: #0066cc;
    border-color: #0066cc;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 102, 204, 0.4);
}

/* Button Settings Panel */
.button-settings-panel {
    margin-top: 20px;
    padding: 20px;
    background: #ffffff;
    border: 2px solid #e1e1e1;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.button-settings-panel h5 {
    margin-top: 0;
    color: #0066cc;
    border-bottom: 2px solid #e1e1e1;
    padding-bottom: 10px;
}

/* Form Styling */
.button-settings-form .form-row {
    margin-bottom: 20px;
}

.button-settings-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.button-settings-form input[type="text"],
.button-settings-form textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e1e1;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.button-settings-form input[type="text"]:focus,
.button-settings-form textarea:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.button-settings-form .button {
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.button-settings-form .button-primary {
    background: #0066cc;
    color: #ffffff;
    border: none;
}

.button-settings-form .button-primary:hover {
    background: #0052a3;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 102, 204, 0.3);
}

.button-settings-form .button-secondary {
    background: #6c757d;
    color: #ffffff;
    border: none;
}

.button-settings-form .button-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

/* Accordion (messages tab) */
.qrcdr-accordion {
    border: 3px solid transparent;
    border-image: linear-gradient(90deg, #ffecd2 0%, #fcb69f 25%, #a1c4fd 50%, #c2e9fb 75%, #fcb69f 100%);
    border-image-slice: 1;
    border-radius: 8px;
    overflow: hidden;
    margin: 10px 0 20px;
    
}
  .qrcdr-acc-item + .qrcdr-acc-item { border-top: 3px solid transparent;
    border-image: linear-gradient(90deg, #ffecd2 0%, #fcb69f 25%, #a1c4fd 50%, #c2e9fb 75%, #fcb69f 100%);
    border-image-slice: 2; }
.qrcdr-acc-header {
    width: 100%;
    text-align: right;
    padding: 12px 16px;
    background: #fff;
    border: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    border: 4px solid transparent !important;
    border-image: var(--rainbow-gradient) !important;
    border-image-slice: 3;
}
.qrcdr-acc-header:hover, .qrcdr-acc-header:active { 
    background: linear-gradient(90deg, #ffecd2 0%, #fcb69f 25%, #a1c4fd 50%, #c2e9fb 75%, #fcb69f 100%);
    border: 4px solid transparent;
    border-image: linear-gradient(90deg, #ff9800 0%, #ff3d00 20%, #f50057 40%, #3f51b5 60%, #00bcd4 80%, #4caf50 100%);
    border-image-slice: 4;
}
.qrcdr-acc-arrow { color: var(--rainbow-gradient) !important; font-weight: 600; }
.qrcdr-acc-body { 
    padding: 12px 16px; 
    border: 2px solid transparent;
    border-image-slice: 4;

    background: var(--rainbow-gradient-soft) !important;
}

/* Icon Selector Button */
.icon-selector-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.icon-selector-container input {
    flex: 1;
}

.icon-selector-container .button {
    white-space: nowrap;
}

/* Responsive Design */
@media (max-width: 768px) {
    .button-tabs {
        justify-content: center;
    }
    
    .static-button-tab {
        min-width: 100px;
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .button-settings-panel {
        padding: 15px;
    }
}
</style>

<!-- JavaScript functions for button settings -->
<script type="text/javascript">
// Accordion toggle for messages tab
function qrcdrToggleAccordion(headerButton) {
    const item = headerButton.closest('.qrcdr-acc-item');
    const body = item.querySelector('.qrcdr-acc-body');
    const arrow = headerButton.querySelector('.qrcdr-acc-arrow');
    const isOpen = body.style.display !== 'none';
    body.style.display = isOpen ? 'none' : 'block';
    if (arrow) arrow.textContent = isOpen ? '▾' : '▴';
}
// Function to show static button settings
function showStaticButtonSettings(buttonName, event) {
    // Remove active class from all tabs
    document.querySelectorAll('.static-button-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Add active class to clicked tab
    if (event && event.target) {
        event.target.classList.add('active');
    }
    
    // Show settings panel
    const settingsPanel = document.getElementById('static-button-settings');
    if (settingsPanel) {
        settingsPanel.style.display = 'block';
    }
    
    // Load button content based on current language
    const currentLang = getCurrentLanguage();
    loadStaticButtonContent(buttonName, currentLang);
}

// Function to get current language
function getCurrentLanguage() {
    const activeLangTab = document.querySelector('.lang-tab-btn.active');
    if (activeLangTab) {
        if (activeLangTab.textContent.includes('فارسی')) return 'fa';
        if (activeLangTab.textContent.includes('English')) return 'en';
        if (activeLangTab.textContent.includes('العربية')) return 'ar';
        if (activeLangTab.textContent.includes('Türkçe')) return 'tr';
        if (activeLangTab.textContent.includes('Русский')) return 'ru';
        if (activeLangTab.textContent.includes('Français')) return 'fr';
        if (activeLangTab.textContent.includes('Deutsch')) return 'de';
    }
    return 'fa'; // Default to Persian
}

// Function to load static button content
function loadStaticButtonContent(buttonName, language) {
    const contentDiv = document.getElementById('static-button-content');
    if (!contentDiv) return;
    
    // Create form fields for the selected button
    const formHTML = `
        <form method="POST" class="button-settings-form">
            <input type="hidden" name="button_name" value="${buttonName}">
            <input type="hidden" name="button_language" value="${language}">
            
            <div class="form-row">
                <label>نام دکمه (${getLanguageLabel(language)}):</label>
                <input type="text" name="static_${language}_button_${buttonName}" 
                       value="${getCurrentButtonValue(buttonName, language)}" 
                       placeholder="نام دکمه ${buttonName}" style="width: 100%;" />
            </div>
            
            <div class="form-row">
                <label>آیکن دکمه:</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="text" name="static_${language}_icon_${buttonName}" 
                           value="${getCurrentIconValue(buttonName, language)}" 
                           placeholder="نام آیکن (مثال: fas fa-link)" style="flex: 1;" />
                    <button type="button" class="button button-secondary" onclick="openIconSelector('${buttonName}', '${language}')">
                        انتخاب آیکن
                    </button>
                </div>
            </div>
            
            <div class="form-row">
                <label>توضیحات دکمه:</label>
                <textarea name="static_${language}_description_${buttonName}" 
                          placeholder="توضیحات دکمه ${buttonName}" 
                          style="width: 100%; height: 60px;">${getCurrentDescriptionValue(buttonName, language)}</textarea>
            </div>
            
            <div class="form-row">
                <button type="submit" class="button button-primary">ذخیره تنظیمات دکمه</button>
            </div>
        </form>
    `;
    
    contentDiv.innerHTML = formHTML;
}

// Function to get language label
function getLanguageLabel(language) {
    const labels = {
        'fa': 'فارسی',
        'en': 'English',
        'ar': 'العربية',
        'tr': 'Türkçe',
        'ru': 'Русский',
        'fr': 'Français',
        'de': 'Deutsch'
    };
    return labels[language] || language;
}

// Function to get current button value
function getCurrentButtonValue(buttonName, language) {
    // Try to get value from database via AJAX or use default
    const defaultValues = {
        'link': 'لینک',
        'text': 'متن',
        'email': 'ایمیل',
        'location': 'لوکیشن',
        'tel': 'تلفن',
        'sms': 'پیامک',
        'whatsapp': 'واتساپ',
        'instagram': 'اینستاگرام',
        'zoom': 'زوم',
        'wifi': 'وای‌فای',
        'vcard': 'وی‌کارت',
        'event': 'رویداد',
        'paypal': 'پی‌پال',
        'bitcoin': 'بیت‌کوین',
        'ethereum': 'اتریوم'
    };
    
    // For now, return default value based on language
    if (language === 'fa') {
        return defaultValues[buttonName] || buttonName.charAt(0).toUpperCase() + buttonName.slice(1);
    } else {
        return buttonName.charAt(0).toUpperCase() + buttonName.slice(1);
    }
}

// Function to get current icon value
function getCurrentIconValue(buttonName, language) {
    // Default icons for each button
    const defaultIcons = {
        'link': 'fas fa-link',
        'text': 'fas fa-font',
        'email': 'fas fa-envelope',
        'location': 'fas fa-map-marker-alt',
        'tel': 'fas fa-phone',
        'sms': 'fas fa-sms',
        'whatsapp': 'fab fa-whatsapp',
        'instagram': 'fab fa-instagram',
        'zoom': 'fas fa-video',
        'wifi': 'fas fa-wifi',
        'vcard': 'fas fa-address-card',
        'event': 'fas fa-calendar-alt',
        'paypal': 'fab fa-paypal',
        'bitcoin': 'fab fa-bitcoin',
        'ethereum': 'fab fa-ethereum'
    };
    return defaultIcons[buttonName] || 'fas fa-cog';
}

// Function to get current description value
function getCurrentDescriptionValue(buttonName, language) {
    // Default descriptions for each button
    const defaultDescriptions = {
        'link': 'ایجاد QR کد برای لینک',
        'text': 'ایجاد QR کد برای متن',
        'email': 'ایجاد QR کد برای ایمیل',
        'location': 'ایجاد QR کد برای لوکیشن',
        'tel': 'ایجاد QR کد برای تلفن',
        'sms': 'ایجاد QR کد برای پیامک',
        'whatsapp': 'ایجاد QR کد برای واتساپ',
        'instagram': 'ایجاد QR کد برای اینستاگرام',
        'zoom': 'ایجاد QR کد برای زوم',
        'wifi': 'ایجاد QR کد برای وای‌فای',
        'vcard': 'ایجاد QR کد برای وی‌کارت',
        'event': 'ایجاد QR کد برای رویداد',
        'paypal': 'ایجاد QR کد برای پی‌پال',
        'bitcoin': 'ایجاد QR کد برای بیت‌کوین',
        'ethereum': 'ایجاد QR کد برای اتریوم'
    };
    
    if (language === 'fa') {
        return defaultDescriptions[buttonName] || `توضیحات پیش‌فرض برای دکمه ${buttonName}`;
    } else {
        return `Default description for ${buttonName} button`;
    }
}

// Function to open icon selector
function openIconSelector(buttonName, language) {
    // This would open a popup with icon selection
    // For now, show a simple alert
    alert('انتخاب آیکن - این قابلیت در نسخه بعدی اضافه خواهد شد');
}

// Function to show dynamic button settings
function showDynamicButtonSettings(buttonName, event) {
    // Remove active class from all tabs
    document.querySelectorAll('.dynamic-button-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Add active class to clicked tab
    if (event && event.target) {
        event.target.classList.add('active');
    }
    
    // Load dynamic button content
    loadDynamicButtonContent(buttonName);
}

// Function to load dynamic button content
function loadDynamicButtonContent(buttonName) {
    // This would load the dynamic button settings
    // For now, just show a message
    console.log('Loading dynamic button settings for:', buttonName);
}
</script>
<?php