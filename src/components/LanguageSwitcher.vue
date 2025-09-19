<template>
  <div class="language-switcher">
    <v-menu offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on" class="language-btn">
          <flag :iso="currentLanguageIcon" :title="currentLanguageIconName" />
        </v-btn>
      </template>
      <v-list>
        <v-list-item
          v-for="lang in availableLanguages"
          :key="lang.code"
          @click="changeLanguage(lang.code)"
          :class="{ 'active-language': currentLanguage === lang.code }"
        >
          <v-list-item-icon>
            <flag
              :iso="selectLanguage(lang.code).Icon"
              :title="selectLanguage(lang.code).Name"
            />
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ lang.name }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script>
export default {
  name: "LanguageSwitcher",
  props: {
    defaultLanguage: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      availableLanguages: [
        {
          code: "fa",
          name: this.$t("language.farsi"),
          icon: "ir",
        },
        {
          code: "en",
          name: this.$t("language.english"),
          icon: "gb",
        },
        {
          code: "ar",
          name: this.$t("language.arabic"),
          icon: "sa",
        },
        {
          code: "tr",
          name: this.$t("language.turkish"),
          icon: "tr",
        },
        {
          code: "ru",
          name: this.$t("language.russian"),
          icon: "ru",
        },
        {
          code: "fr",
          name: this.$t("language.french"),
          icon: "fr",
        },
        {
          code: "de",
          name: this.$t("language.german"),
          icon: "de",
        },
      ],
    };
  },
  computed: {
    currentLanguage() {
      return this.$i18n.locale;
    },
    currentLanguageIcon() {
      const lang = this.availableLanguages.find(
        (l) => l.code === this.currentLanguage
      );
      return lang ? lang.icon : "ir";
    },
    currentLanguageIconName() {
      const lang = this.availableLanguages.find(
        (l) => l.code === this.currentLanguage
      );
      return lang ? lang.name : this.$t("language.farsi");
    },
  },
  methods: {
    selectLanguage(lang) {
      switch (lang) {
        case "fa":
          return {
            Icon: "ir",
            Name: this.$t("language.farsi"),
          };
        case "en":
          return {
            Icon: "gb",
            Name: this.$t("language.english"),
          };
        case "ru":
          return {
            Icon: "ru",
            Name: this.$t("language.russian"),
          };
        case "tr":
          return {
            Icon: "tr",
            Name: this.$t("language.turkish"),
          };
        case "ar":
          return {
            Icon: "sa",
            Name: this.$t("language.arabic"),
          };
        case "fr":
          return {
            Icon: "fr",
            Name: this.$t("language.french"),
          };
        case "de":
          return {
            Icon: "de",
            Name: this.$t("language.german"),
          };
      }
    },
    changeLanguage(langCode) {
      this.$i18n.locale = langCode;
      localStorage.setItem("preferred-language", langCode);

      if (langCode === "fa" || langCode === "ar") {
        document.documentElement.setAttribute("dir", "rtl");
        document.documentElement.setAttribute("lang", langCode);
      } else {
        document.documentElement.setAttribute("dir", "ltr");
        document.documentElement.setAttribute("lang", langCode);
      }

      this.$emit("language-changed", langCode);
    },
    setInitialLanguage() {
      let initialLang = this.defaultLanguage;
      if (!initialLang) {
        initialLang = localStorage.getItem("preferred-language");
      }
      const validLang = this.availableLanguages.find(
        (lang) => lang.code == initialLang
      )?.code;
      if (validLang) {
        this.$i18n.locale = validLang;
        localStorage.setItem("preferred-language", validLang);
        if (validLang === "fa" || validLang === "ar") {
          document.documentElement.setAttribute("dir", "rtl");
          document.documentElement.setAttribute("lang", validLang);
        } else {
          document.documentElement.setAttribute("dir", "ltr");
          document.documentElement.setAttribute("lang", validLang);
        }
      }
    },
  },
  mounted() {
    this.setInitialLanguage();
  },
};
</script>

<style scoped>
.language-switcher {
  position: fixed;
  top: 20px;
  left: 20px;
  z-index: 1000;
}

.language-btn {
  background-color: rgba(255, 255, 255, 0.9) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(0, 0, 0, 0.1);
}

.active-language {
  background-color: #f0f0f0;
}

/* [dir="rtl"] .language-switcher { */
/* right: auto; */
/* left: 20px; */
/* } */

/* Tablet */
@media (max-width: 768px) {
  .language-switcher {
    top: 15px;
    left: 15px;
  }

  /* [dir="rtl"] .language-switcher { */
  /* right: auto; */
  /* left: 15px; */
  /* } */

  .language-btn {
    width: 40px !important;
    height: 40px !important;
    min-width: 40px !important;
    padding: 0 !important;
  }
}

/* Mobile */
@media (max-width: 480px) {
  .language-switcher {
    top: 10px;
    left: 10px;
  }

  /* [dir="rtl"] .language-switcher { */
  /* right: auto; */
  /* left: 10px; */
  /* } */

  .language-btn {
    width: 35px !important;
    height: 35px !important;
    padding: 0 !important;
    min-width: 35px !important;
  }
}

/* Small mobile */
@media (max-width: 360px) {
  .language-switcher {
    top: 8px;
    left: 8px;
  }

  /* [dir="rtl"] .language-switcher { */
  /* right: auto;
    left: 8px; */
  /* } */

  .language-btn {
    width: 32px !important;
    height: 32px !important;
    min-width: 32px !important;
    padding: 0 !important;
  }
}
</style>
