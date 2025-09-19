<template>
  <v-app>
    <language-switcher
      @language-changed="onLanguageChanged"
      :defaultLanguage="currentLang"
    />
    <div class="qrcdr-container">
      <!-- <div class="header">{{ $t('header') }}</div> -->
      <component
        v-if="settings?.visibleTitle"
        :is="settings.selectedTagTitle"
        class="topTitle"
        >{{ $t(topTitle) }}</component
      >
      <component
        v-if="settings?.visibleText"
        :is="settings.selectedTagText"
        class="topDescription"
        >{{ $t(settings.topText) }}</component
      >
      <nav-tabs
        :settings="settings"
        v-model="activeView"
        v-if="settings?.visibleNavTab"
      >
        <template v-slot:static>
          <static-tab
            :settings="settings"
            @button-clicked="
              selectedButton = $event;
              activeView = 'static-details';
            "
          ></static-tab>
        </template>
        <template v-slot:dynamic>
          <dynamic-tab :settings="settings"></dynamic-tab>
        </template>
      </nav-tabs>

      <static-tab
        v-else-if="settings?.visibleStatic"
        :settings="settings"
        @button-clicked="
          selectedButton = $event;
          activeView = 'static-details';
        "
      ></static-tab>
      <!-- <static-details
        v-if="activeView === 'static-details'"
        :settings="settings"
        :selected-button="selectedButton"
        @back-to-static="
          activeView = 0;
          selectedButton = null;
        "
      ></static-details> -->
      <dynamic-tab
        v-else-if="settings?.visibleDynamic"
        :settings="settings"
      ></dynamic-tab>
    </div>
  </v-app>
</template>

<script>
import NavTabs from "./components/NavTabs.vue";
import StaticTab from "./components/StaticTab.vue";
import DynamicTab from "./components/DynamicTab.vue";
import LanguageSwitcher from "./components/LanguageSwitcher.vue";
import { EventBus } from "./components/eventBus.js";

export default {
  name: "App",
  props: {
    settings: {
      type: Object,
      default: () => ({}),
    },
  },
  components: {
    NavTabs,
    StaticTab,
    DynamicTab,
    LanguageSwitcher,
  },
  data() {
    return {
      currentLang: "fa",
      topTitle: this.settings.topTitle || "online_qr_title",
      activeView: 0,
      selectedButton: null,
      selected: 1,
      activeMenu: 0,
      menuItems: [
        { text: "menuItems.info", icon: "fas fa-info-circle" },
        { text: "menuItems.design", icon: "fas fa-pencil-ruler" },
        { text: "menuItems.colors", icon: "fas fa-palette" },
        { text: "menuItems.logo", icon: "fas fa-image" },
        { text: "menuItems.frame", icon: "fas fa-border-all" },
        { text: "menuItems.options", icon: "fas fa-cogs" },
      ],
    };
  },
  watch: {
    activeView(newVal) {
      if (newVal === "static-details") {
        this.topTitle = "static_qr_title";
      } else if (newVal === "1" || newVal === 1) {
        this.topTitle = "dynamic_qr_title";
      } else {
        this.topTitle = "online_qr_title";
      }
    },
  },
  created() {
    this.currentLang = localStorage.getItem("preferred-language") || "fa";
  },
  mounted() {
    console.log("App mounted with activeMenu:", this.activeMenu);
    EventBus.$on("updateTopTitle", (title) => {
      this.topTitle = title;
    });
  },
  beforeDestroy() {
    EventBus.$off("updateTopTilte");
  },
  methods: {
    onLanguageChanged(langCode) {
      console.log("Language changed to:", langCode);

      this.$vuetify.rtl = langCode === "fa" || langCode === "ar";
      localStorage.setItem("preferred-language", langCode);
    },
  },
};
</script>

<style scoped>
* {
  box-sizing: border-box;
}
.qrcdr-container {
  display: flex;
  flex-direction: column;
  margin-top: 35px;
  width: 100%;
  max-width: 1440px;
  max-height: 100%;
  height: 100%;
  margin-left: auto;
  margin-right: auto;
  padding-left: 20px;
  padding-right: 20px;
  text-align: center;
  justify-content: center !important;
  justify-items: center !important;
  align-items: center !important;
  justify-self: center;
}
.header {
  width: 100%;
  max-width: 1440px;
  height: 182px;
  margin-top: 35px;
  margin-bottom: 53px;
  background-color: azure;
}
.topTitle {
  margin-bottom: 23px;
  background-color: v-bind("settings.topBgColor");
  min-width: 200px;
  max-width: 90%;
  padding: 15px 25px;
  border-radius: 50px;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  font-size: 24px;
  line-height: 1.5;
}
.topDescription {
  margin-bottom: 23px;
  text-align: center;
  max-width: 90%;
}
.content-area {
  min-height: 400px;
  margin-top: 20px;
}

/* Tablet and smaller desktop */
@media (max-width: 1024px) {
  .qrcdr-container {
    padding-left: 15px;
    padding-right: 15px;
  }
  .topTitle {
    font-size: 20px;
    padding: 12px 20px;
  }
}

/* Tablet */
@media (max-width: 768px) {
  .qrcdr-container {
    margin-top: 20px;
    padding-left: 10px;
    padding-right: 10px;
  }
  .content-area {
    margin-top: 10px;
  }
  .topTitle {
    font-size: 18px;
    padding: 10px 15px;
    min-width: 150px;
  }
  .topDescription {
    font-size: 14px;
  }
  .header {
    height: 120px;
    margin-top: 20px;
    margin-bottom: 30px;
  }
}

/* Mobile */
@media (max-width: 480px) {
  .qrcdr-container {
    margin-top: 15px;
    padding-left: 5px;
    padding-right: 5px;
  }
  .topTitle {
    font-size: 16px;
    padding: 8px 12px;
    min-width: 120px;
  }
  .topDescription {
    font-size: 12px;
  }
  .header {
    height: 80px;
    margin-top: 15px;
    margin-bottom: 20px;
  }
}

/* Small mobile */
@media (max-width: 360px) {
  .qrcdr-container {
    margin-top: 10px;
    padding-left: 2px;
    padding-right: 2px;
  }
  .topTitle {
    font-size: 14px;
    padding: 6px 10px;
    min-width: 100px;
  }
  .topDescription {
    font-size: 11px;
  }
}

.content {
  text-align: center;
  margin-left: 120px;
}

@media (max-width: 768px) {
  .content {
    margin-left: 0;
    margin-bottom: 80px;
  }
}
</style>
