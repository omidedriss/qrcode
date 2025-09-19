import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import App from './App.vue';
import VueI18n from 'vue-i18n';
import messages from './messages';
import "./assets/style.css";
import '@fortawesome/fontawesome-free/css/all.min.css';
import '@mdi/font/css/materialdesignicons.min.css';
import './global.css'
import FlagIcon from 'vue-flag-icon';

Vue.use(FlagIcon);
Vue.use(Vuetify, {
  rtl: true
});
Vue.use(VueI18n);

const vuetify = new Vuetify({
  rtl: true,
  theme: {
    options: {
      customProperties: true,
    },
  },
  breakpoint: {
    mobileBreakpoint: 'md'  // md instead of sm - includes tablets too
  }
});

const settings = {
  google_api_key: 'YOUR-API-KEY',         // https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key
  lat: '40.7127837',                      // Initial latitude for the location map
  lng: '-74.00594130000002',
  lan: '40.7127837',
  location: true,
  locale: 'fa',
  lang: 'fa',
  OrginBgColor: '#ffffff',
  menuBgColor: '#403199',
  activeMenuColor: '#ab9fee',
  iconColor: '#ffffff',
  selectedTagTitle: 'h2',
  visibleTitle: true,
  visibleText: true,
  visibleTabs: true,
  visibleNavTab: true,    // Only one should be true from these 3
  visibleDynamic: false,  // Only one should be true from these 3
  visibleStatic: false,   // Only one should be true from these 3
  selectedTagText: 'h2',
  selectedTagTab: 'h2',
  topTitle: 'topTitle',
  topText: 'topText',
  topBgColor: '#fbe0e3',
  tabSize: '50%',
  textColor: '#333',
  textSize: '12px !important',
  tabTextSize: '16px',
  tabTextActiveSize: '17px',
  tabBgColor: '#ffffff',
  activeTabColor: '#403199',
  buttonSize: '100px',
  iconSize: '12px',
  iconBgColor: '#ff4d4d',
  butttonColor: '#e9354d',
  iconCloseDescriptionColor: '#ff4d4d',
  iconCloseDescriptionHoverColor: '#403199',
  buttonColor: 'primary',
  bottomLineColor: '#403199',
  gradientColors: ['#e9354d', '#e9354d', '#e9354d', '#e9354d', '#403199', '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#403199', '#e9354d', '#e9354d', '#e9354d', '#e9354d'],
  gradientColorsIcon: ['#e9354d', '#403199', '#ffffff'],
  previewBgColor: '#f9f9f9',
  indicatorBorderColor: '#f0f0ff',
  staticTabText: 'staticTabText',
  dynamicTabText: 'dynamicTabText',
  previewWidth: '30%',
  frameImage: 'img/cell.png',
  defaultImage: 'https://via.placeholder.com/150',
  defaultDoc: 'defaultDoc',
  padding: '20px',
  gap: '30px',
  buttonGap: '50px',
  buttonPadding: '20px',
  previewGap: '20px',
  textAreaWidth: '75%',
  textMargin: '10px 0',
  textLineHeight: '1.5',
  phoneWidth: '250px',
  phoneHeight: '500px',
  frameBorderRadius: '45px',
  innerImageTop: '25px',
  innerImageLeft: '5px',
  innerImageWidth: '240px',
  innerImageHeight: '450px',
  innerImageBorderRadius: '15px',
  detailsPanelHeight: '200px',
  detailsPanelBgColor: 'white',
  detailsPanelTextColor: 'black',
  detailsPanelPadding: '20px',
  detailsPanelBorderRadius: '15px',
  togglePanelBgColor: 'white',
  togglePanelTextColor: 'black',
  togglePanelBottom: '10px',
  bottomNavBgColor: '#333',
  bottomNavHeight: '60px',
  navButtonSize: '50px',
  navButtonBgColor: '#666',
  navButtonTextColor: 'white',
  sheetContentPadding: '20px !important',
  sheetTextColor: '#333',
  textSize_line1: '16px !important',
  textSize_line2: '14px !important',
  textSize_line1_mobile: '14px !important',
  textSize_line2_mobile: '12px !important',
  textSize_mobile: '14px !important',
  textSize_line1_tablet: '14px !important',
  textSize_line2_tablet: '12px !important',
  textSize_tablet: '14px !important',
  smallerTextSize: '12px !important',
  textSize_line1_mobile_small: '12px !important',
  textSize_line2_mobile_small: '10px !important',
  textSize_mobile_small: '12px !important',
  textSize_line1_tablet_small: '13px !important',
  textSize_line2_tablet_small: '11px !important',
  textSize_tablet_small: '13px !important',
  // Additional responsive font sizes for better scaling
  textSize_line1_desktop: '16px !important',
  textSize_line2_desktop: '14px !important',
  textSize_desktop: '16px !important',
  fontFamily_line1: '"IRANYekanX", Sans-serif !important',
  fontFamily_line2: '"Vazir", Sans-serif !important',
  fontWeight_line1: '900',
  fontWeight_line2: '300',
  // Font settings for different languages and device types
  fontFamily_line1_en: 'timesNewRoman, Sans-serif',
  fontFamily_line2_en: 'timesNewRoman, Sans-serif',
  fontWeight_line1_en: '700',
  fontWeight_line2_en: '400',
  fontFamily_line1_ar: '"Arial", Sans-serif',
  fontFamily_line2_ar: '"Arial", Sans-serif',
  fontWeight_line1_ar: '700',
  fontWeight_line2_ar: '400',
  fontFamily_line1_tr: '"Arial", Sans-serif',
  fontFamily_line2_tr: '"Arial", Sans-serif',
  fontWeight_line1_tr: '700',
  fontWeight_line2_tr: '400',
  fontFamily_line1_ru: '"Arial", Sans-serif',
  fontFamily_line2_ru: '"Arial", Sans-serif',
  fontWeight_line1_ru: '700',
  fontWeight_line2_ru: '400',
  dynamicButtons: [
    {
      icon: 'fas fa-id-card',
      text: 'dynamicButtons.business_card',
      text2: 'dynamicButtons.business_card_text2',
      subtext: 'dynamicButtons.business_card_subtext',
      image: 'img/1.jpg',
      doc: 'dynamicButtons.business_card_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-share-alt',
      text: 'dynamicButtons.social_media',
      text2: 'dynamicButtons.social_media_text2',
      subtext: 'dynamicButtons.social_media_subtext',
      image: 'img/2.jpg',
      doc: 'dynamicButtons.social_media_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
        color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-images',
      text: 'dynamicButtons.photo_gallery',
      text2: 'dynamicButtons.photo_gallery_text2',
      subtext: 'dynamicButtons.photo_gallery_subtext',
      image: 'img/3.jpg',
      doc: 'dynamicButtons.photo_gallery_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-video',
      text: 'dynamicButtons.video_gallery',
      text2: 'dynamicButtons.video_gallery_text2',
      subtext: 'dynamicButtons.video_gallery_subtext',
      image: 'https://via.placeholder.com/150?text=Video',
      doc: 'dynamicButtons.video_gallery_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-music',
      text: 'dynamicButtons.audio_gallery',
      text2: 'dynamicButtons.audio_gallery_text2',
      subtext: 'dynamicButtons.audio_gallery_subtext',
      image: 'https://via.placeholder.com/150?text=Audio',
      doc: 'dynamicButtons.audio_gallery_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-headphones',
      text: 'dynamicButtons.audio_pro_gallery',
      text2: 'dynamicButtons.audio_pro_gallery_text2',
      subtext: 'dynamicButtons.audio_pro_gallery_subtext',
      image: 'https://via.placeholder.com/150?text=AudioPro',
      doc: 'dynamicButtons.audio_pro_gallery_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-comment-alt',
      text: 'dynamicButtons.feedback',
      text2: 'dynamicButtons.feedback_text2',
      subtext: 'dynamicButtons.feedback_subtext',
      image: 'https://via.placeholder.com/150?text=Feedback',
      doc: 'dynamicButtons.feedback_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-file-alt',
      text: 'dynamicButtons.request_form',
      text2: 'dynamicButtons.request_form_text2',
      subtext: 'dynamicButtons.request_form_subtext',
      image: 'https://via.placeholder.com/150?text=Form',
      doc: 'dynamicButtons.request_form_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-link',
      text: 'dynamicButtons.link',
      text2: 'dynamicButtons.link_text2',
      subtext: 'dynamicButtons.link_subtext',
      image: 'https://via.placeholder.com/150?text=Link',
      doc: 'dynamicButtons.link_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-globe',
      text: 'dynamicButtons.website',
      text2: 'dynamicButtons.website_text2',
      subtext: 'dynamicButtons.website_subtext',
      image: 'https://via.placeholder.com/150?text=Site',
      doc: 'dynamicButtons.website_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-utensils',
      text: 'dynamicButtons.online_menu',
      text2: 'dynamicButtons.online_menu_text2',
      subtext: 'dynamicButtons.online_menu_subtext',
      image: 'https://via.placeholder.com/150?text=Menu',
      doc: 'dynamicButtons.online_menu_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/menu',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-shield-alt',
      text: 'dynamicButtons.insurance',
      text2: 'dynamicButtons.insurance_text2',
      subtext: 'dynamicButtons.insurance_subtext',
      image: 'https://via.placeholder.com/150?text=Insurance',
      doc: 'dynamicButtons.insurance_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cardVisit',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-shopping-cart',
      text: 'dynamicButtons.online_shop',
      text2: 'dynamicButtons.online_shop_text2',
      subtext: 'dynamicButtons.online_shop_subtext',
      image: 'https://via.placeholder.com/150?text=Shop',
      doc: 'dynamicButtons.online_shop_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/bShop',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
  {
      icon: 'fa-mobile-alt',
      text: 'dynamicButtons.app',
      text2: 'dynamicButtons.app_text2',
      subtext: 'dynamicButtons.app_subtext',
      image: 'https://via.placeholder.com/150?text=Booking',
      doc: 'dynamicButtons.app_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/appointment',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-calendar-alt',
      text: 'dynamicButtons.booking',
      text2: 'dynamicButtons.booking_text2',
      subtext: 'dynamicButtons.booking_subtext',
      image: 'https://via.placeholder.com/150?text=Booking',
      doc: 'dynamicButtons.booking_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/appointment',
      link2: "#",
      color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-building',
      text: 'dynamicButtons.commercial_complex',
      text2: 'dynamicButtons.commercial_complex_text2',
      subtext: 'dynamicButtons.commercial_complex_subtext',
      image: 'https://via.placeholder.com/150?text=Complex',
      doc: 'dynamicButtons.commercial_complex_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/cPage',
      link2: "#",
        color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-box-open',
      text: 'dynamicButtons.product',
      text2: 'dynamicButtons.product_text2',
      subtext: 'dynamicButtons.product_subtext',
      image: 'https://via.placeholder.com/150?text=Product',
      doc: 'dynamicButtons.product_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/product/shopt',
      link2: "#",
        color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-tag',
      text: 'dynamicButtons.product_label',
      text2: 'dynamicButtons.product_label_text2',
      subtext: 'dynamicButtons.product_label_subtext',
      image: 'https://via.placeholder.com/150?text=Label',
      doc: 'dynamicButtons.product_label_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/produc/label',
      link2: "#",
        color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-gem',
      text: 'dynamicButtons.gold_label',
      text2: 'dynamicButtons.gold_label_text2',
      subtext: 'dynamicButtons.gold_label_subtext',
      image: 'https://via.placeholder.com/150?text=Gold',
      doc: 'dynamicButtons.gold_label_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/goldlabel',
      link2: "#",
        color1:"#e9354d",
      color2:"#e9354d",
    },
    {
      icon: 'fas fa-book',
      text: 'dynamicButtons.catalog',
      text2: 'dynamicButtons.catalog_text2',
      subtext: 'dynamicButtons.catalog_subtext',
      image: 'https://via.placeholder.com/150?text=Catalog',
      doc: 'dynamicButtons.catalog_doc',
      visible: true,
      link1: 'https://core-qr.terminalads.com/#/qr/catalog',
      link2: "#",
        color1:"#e9354d",
      color2:"#e9354d",
    },
  ],
  buttons: [
    { icon: 'fas fa-link', text: 'buttons.link', value: 'LinkTab', visible: true, },
    { icon: 'fas fa-font', text: 'buttons.text', value: 'TextTab', visible: true, },
    { icon: 'fas fa-envelope', text: 'buttons.email', value: 'EmailTab', visible: true, },
    { icon: 'fas fa-map-marker-alt', text: 'buttons.location', value: 'LocationTab', visible: true, },
    { icon: 'fas fa-phone', text: 'buttons.tel', value: 'TelTab', visible: true, },
    { icon: 'fas fa-sms', text: 'buttons.sms', value: 'SmsTab', visible: true, },
    { icon: 'fab fa-whatsapp', text: 'buttons.whatsapp', value: 'WhatsAppTab', visible: true, },
    { icon: 'fab fa-instagram', text: 'buttons.instagram', value: 'instagramTab', visible: true, },
    { icon: 'fas fa-video', text: 'buttons.zoom', value: 'ZoomTab', visible: true, },
    { icon: 'fas fa-wifi', text: 'buttons.wifi', value: 'WifiTab', visible: true, },
    { icon: 'fas fa-address-card', text: 'buttons.vcard', value: 'VCardTab', visible: true, },
    { icon: 'fas fa-calendar-alt', text: 'buttons.event', value: 'EventTab', visible: true, },
    { icon: 'fab fa-paypal', text: 'buttons.paypal', value: 'PaypalTab', visible: true, },
    { icon: 'fab fa-bitcoin', text: 'buttons.bitcoin', value: 'BitcoinTab', visible: true, },
    { icon: 'fab fa-ethereum', text: 'buttons.ethereum', value: 'EthereumTab', visible: true, },
  ],
  menuItems: [
    { icon: 'fas fa-info-circle', text: 'menuItems.info', value: 'info' },
    { icon: 'fas fa-pencil-ruler', text: 'menuItems.design', value: 'DesignPanel' },
    { icon: 'fas fa-palette', text: 'menuItems.colors', value: 'ColorsPanel' },
    { icon: 'fas fa-image', text: 'menuItems.logo', value: 'LogoPanel' },
    { icon: 'fas fa-border-all', text: 'menuItems.frame', value: 'FramePanel' },
    { icon: 'fas fa-cogs', text: 'menuItems.options', value: 'OptionsPanel' },
  ],
  DesignPanel: {
    pattern: "square",
    markerOut: "square",
    markerIn: "square",
    markersColor: false,
    markerOutColor: "#000000",
    markerInColor: "#000000",
    differentMarkersColor: false,
    markerTopRightOutline: "#000000",
    markerTopRightCenter: "#000000",
    markerBottomLeftOutline: "#000000",
    markerBottomLeftCenter: "#000000",
  },
  backgroundColor: "#FFFFFF",
  logo: null,
  LogoPanel: {
    optionLogo: "",
    logoFile: null,
    logoError: "",
    noLogoBg: true,
    logoSize: 0,
    customWatermark: "",
    watermarks: [],
    uploader: true,
  },
  FramePanel: {
    outerFrame: "",
    frameLabel: "",
    labelFont: "",
    labelTextSize: 0,
    customFrameColor: false,
    frameColor: "",
    frames: {},
    fonts: [],
  },
  OptionsPanel: {
    size: 0,
    level: "",
    sizeOptions: [],
    levelOptions: [],
  },
  ColorsPanel: {
    backcolor: '',
    frontcolor: '',
    gradientColor: '',
    transparent: true,
    transparentCode: false,
    negativeQr: false,
    gradient: false,
    radial: true,
    bgImage: '',
    imageZoom: 0,
  },

  // Main site colors - configurable from admin
  primaryColor: '#403199',        // Primary color (purple)
  secondaryColor: '#e9354d',      // Secondary color (pink)

  // Transparent background settings for all areas
  backgroundSettings: {
    // Main page background
    mainBackground: {
      color: '#ffffff',
      image: '',
      transparent: false
    },

    // Main container background
    containerBackground: {
      color: '#ffffff',
      image: '',
      transparent: true
    },

    // Tabs background
    tabsBackground: {
      color: '#ffffff',
      image: '',
      transparent: true
    },

    // Panels background
    panelsBackground: {
      color: '#ffffff',
      image: '',
      transparent: true
    },

    // Forms background
    formsBackground: {
      color: '#ffffff',
      image: '',
      transparent: true
    },

    // Preview frame background
    previewBackground: {
      color: '#f9f9f9',
      image: '',
      transparent: true
    },

    // Menu background
    menuBackground: {
      color: '#403199',
      image: '',
      transparent: true
    },

    // Button background
    buttonBackground: {
      color: '#e9354d',
      image: '',
      transparent: true
    }
  },
};

const i18n = new VueI18n({
  locale: getPreferredLanguage(),
  fallbackLocale: 'fa',
  messages,
});

// Function to detect user's preferred language
function getPreferredLanguage() {
  // Check if there's a saved preference in localStorage
  const savedLang = localStorage.getItem('preferred-language');
  if (savedLang && (savedLang === 'fa' || savedLang === 'en' || savedLang === 'ar' || savedLang === 'tr' || savedLang === 'ru' || savedLang === 'fr' || savedLang === 'de')) {
    return savedLang;
  }

  // Check browser language
  const browserLang = navigator.language || navigator.userLanguage;
  if (browserLang.startsWith('fa') || browserLang.startsWith('ar')) {
    return browserLang.startsWith('fa') ? 'fa' : 'ar';
  }
  if (browserLang.startsWith('tr')) {
    return 'tr';
  }
  if (browserLang.startsWith('ru')) {
    return 'ru';
  }
  if (browserLang.startsWith('fr')) {
    return 'fr';
  }
  if (browserLang.startsWith('de')) {
    return 'de';
  }

  // Default to Persian
  return 'fa';
}

// Save language preference when it changes
i18n.afterInit = function () {
  this.$watch('locale', (newLocale) => {
    localStorage.setItem('preferred-language', newLocale);
  });
};
new Vue({
  vuetify,
  i18n,
  render: function (h) {
    console.log('Rendering App with settings:', settings);
    return h(App, { props: { settings } });
  }
}).$mount('#qrcdr-app');