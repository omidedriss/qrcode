<template>
  <!-- <div class="static-buttons" v-if="!showDetails"> -->
  <div class="static-buttons-grid" v-if="!showDetails">
    <v-row>
      <v-col
        v-for="button in visibleButtons"
        :key="button.text"
        :cols="getCols()"
        class="button-col"
      >
        <qrcdr-button
          :icon="button.icon"
          :text="button.text"
          :settings="settings"
          :isDynamic="false"
          @click="clickbtn(button)"
          :isMobile="isMobileBreakpoint"
          :isXs="isXsBreakpoint"
        ></qrcdr-button>
      </v-col>

      <!-- Show All/Show Less button for mobile -->
      <v-col
        v-if="isMobileBreakpoint"
        :cols="isXsBreakpoint ? getCols() * 2 : getCols() * 3"
        class="button-col show-all-button-col"
      >
        <qrcdr-button
          style="
            width: 100%;
            max-width: 100%;
            justify-content: center;
            height: 53px !important;
          "
          :icon="showAllButtons ? 'fas fa-compress' : 'fas fa-th-large'"
          :text="
            showAllButtons
              ? 'dynamicButtons.show_less_static'
              : 'dynamicButtons.show_all_static'
          "
          :settings="settings"
          :subtext="
            showAllButtons
              ? 'dynamicButtons.show_less_subtext'
              : 'dynamicButtons.show_all_subtext'
          "
          :isDynamic="true"
          :isMobile="true"
          :isShowAll="true"
          :isXs="true"
          @click="showAllButtons = !showAllButtons"
        ></qrcdr-button>
      </v-col>
    </v-row>
  </div>
  <StaticDetails
    v-else
    :settings="settings"
    :nameQr="selectedNameButton"
    :selected-button="selectedButton"
    @back-to-static="
      showDetails = false;
      resetTitle();
    "
  />
</template>

<script>
import QrcdrButton from "./QrcdrButton.vue";
import StaticDetails from "./StaticDetails.vue";
import { EventBus } from "./eventBus";

export default {
  name: "StaticTab",
  components: { QrcdrButton, StaticDetails },
  props: {
    settings: {
      type: Object,
      required: true,
      default: () => ({ buttons: [] }), // Fallback to empty buttons array
    },
    selectedButton: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      showDetails: false,
      selectedNameButton: "",
      showAllButtons: false,
    };
  },
  mounted() {
    console.log("StaticTab mounted with settings:", this.settings);

    console.log("Initial showDetails:", this.showDetails);
    console.log("Initial selectedNameButton:", this.selectedNameButton);
    console.log("Initial showAllButtons:", this.showAllButtons);
  },
  computed: {
    isMobileBreakpoint() {
      return this.$vuetify.breakpoint.smAndDown;
    },
    isXsBreakpoint() {
      return this.$vuetify.breakpoint.xs;
    },
    visibleButtons() {
      if (!this.settings || !this.settings.buttons) {
        console.warn("settings.buttons is undefined or null");
        return [];
      }
      if (!this.showAllButtons && this.isMobileBreakpoint) {
        if (this.isXsBreakpoint) return this.settings.buttons.slice(0, 4);
        else return this.settings.buttons.slice(0, 6);
      }
      return this.settings.buttons;
    },
  },
  methods: {
    getCols() {
      if (this.$vuetify.breakpoint.xs) {
        return 6; // 2 buttons per row
      } else if (this.$vuetify.breakpoint.sm) {
        return 4; // 3 buttons per row
      } else {
        return 3; // 4 buttons per row
      }
    },
    resetTitle() {
      EventBus.$emit("updateTopTitle", "static_qr_title");
    },
    clickbtn(button) {
      this.$emit("button-clicked", button);
      this.showDetails = true;
      this.selectedNameButton = button.value;
      EventBus.$emit("updateTopTitle", button.text);
    },
  },
};
</script>

<style scoped>
.static-buttons {
  flex-direction: row;
  flex-wrap: wrap;
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  row-gap: 85px;
  column-gap: 95px;
  margin-top: 65px;
  margin-left: 40px;
  margin-right: 40px;
  justify-content: center;
}

/* Large desktop */
@media (max-width: 1440px) {
  .static-buttons {
    grid-template-columns: repeat(5, 1fr);
    row-gap: 85px;
    column-gap: 95px;
    margin-left: 30px;
    margin-right: 30px;
  }
}

/* Medium desktop */
@media (max-width: 1200px) {
  .static-buttons {
    grid-template-columns: repeat(5, 1fr);
    row-gap: 85px;
    column-gap: 95px;
    margin-left: 25px;
    margin-right: 25px;
  }
}

/* Small desktop */
@media (max-width: 1024px) {
  .static-buttons {
    grid-template-columns: repeat(5, 1fr);
    row-gap: 88px;
    column-gap: 98px;
    margin-left: 0px;
    margin-right: 0px;
  }
}

/* Tablet */
@media (max-width: 768px) {
  .static-buttons {
    grid-template-columns: repeat(4, 1fr);
    row-gap: 58px;
    column-gap: 46px;
    margin-top: 0px;
    margin-left: 0px;
    margin-right: 0px;
    justify-content: center;
  }
}

/* Large mobile */
@media (max-width: 480px) {
  .static-buttons {
    grid-template-columns: repeat(4, 1fr);
    row-gap: 48px;
    column-gap: 36px;
    margin-top: 0px;
    margin-left: 0px;
    margin-right: 0px;
  }
}

/* Medium mobile */
@media (max-width: 425px) {
  .static-buttons {
    grid-template-columns: repeat(3, 1fr);
    row-gap: 38px;
    column-gap: 28px;
    margin-top: 0px;
    margin-left: 0px;
    margin-right: 0px;
  }
}

/* Small mobile */
@media (max-width: 360px) {
  .static-buttons {
    grid-template-columns: repeat(2, 1fr);
    row-gap: 58px;
    column-gap: 46px;
    margin-left: 0px;
    margin-right: 0px;
    margin-top: 0px;
  }
}
.static-buttons-grid {
  width: 100%;
  padding: 20px;
  justify-content: center !important;
  align-items: center !important;
}

.button-col {
  display: flex;
  justify-content: center !important;
  align-items: center !important;
  padding-right: 10px;
  padding-left: 10px;
}

.show-all-button-col {
  flex: 0 0 auto; /* Prevent shrinking */
  width: 80%; /* Take full width */
  margin-left: 10%;
  margin-right: 10%;
}
</style>
