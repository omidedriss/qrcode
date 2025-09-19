<template>
  <div class="dynamic-tab">
    <div class="content-wrapper">
      <!-- Dynamic Buttons Grid using Vuetify v-row and v-col -->
      <div class="dynamic-buttons-grid">
        <v-row>
          <v-col
            v-for="button in visibleDynamicButtons"
            :key="button.text"
            :cols="getCols()"
            class="button-col"
          >
            <qrcdr-button
              :icon="button.icon"
              :text="button.text"
              :settings="settings"
              :subtext="button.subtext"
              :isDynamic="true"
              :isMobile="isMobileBreakpoint"
              :isXs="isXsBreakpoint"
              @click="showDetails(button)"
            ></qrcdr-button>
          </v-col>

          <!-- Show All/Show Less button for mobile -->
          <v-col
            v-if="isMobileBreakpoint"
            :cols="isXsBreakpoint ? getCols() * 2 : getCols() * 3"
            class="button-col-showall show-all-button-col"
          >
            <qrcdr-button
              style="width: 100%; max-width: 100%; height: 53px"
              :icon="
                showAllDynamicButtons ? 'fas fa-compress' : 'fas fa-th-large'
              "
              :text="
                showAllDynamicButtons
                  ? 'dynamicButtons.show_less_dynamic'
                  : 'dynamicButtons.show_all_dynamic'
              "
              :settings="settings"
              :subtext="
                showAllDynamicButtons
                  ? 'dynamicButtons.show_less_subtext'
                  : 'dynamicButtons.show_all_subtext'
              "
              :isDynamic="true"
              :isMobile="true"
              :isShowAll="true"
              :isXs="true"
              @click="toggleShowAll"
            ></qrcdr-button>
          </v-col>
        </v-row>
      </div>

      <div class="preview-area" ref="createButton">
        <div class="phone-frame">
          <template v-if="selectedButton.image">
            <img
              class="inner-image"
              :src="selectedButton.image"
              @error="selectedButton.image = ''"
              alt=""
            />
          </template>
          <template v-else>
            <div class="alt-placeholder">
              {{ $t("inner_image_alt") }}
            </div>
          </template>

          <img
            class="phoneFrame"
            :src="frameImage"
            :alt="$t('phone_frame_alt')"
          />
        </div>

        <div
          v-if="selectedButton.text && !$vuetify.breakpoint.smAndDown"
          class="text-area"
        >
          <div class="text-title">
            <span>{{ $t("create_qr_code", [$t(selectedButton.text)]) }}</span>
          </div>
          <div
            class="justified-text"
            v-html="
              formatDoc($t(selectedButton.doc) || $t(selectedButton.text))
            "
          ></div>
          <v-btn
            class="action-button"
            :color="selectedButton.color1"
            dark
            @click="createAction"
          >
            {{ $t("create_qr_code", [$t(selectedButton.text)]) }}
          </v-btn>
          <v-btn
            class="action-button mx-8"
            :color="selectedButton.color2"
            dark
            @click="createAction2"
          >
            {{ $t("create_qr_code2", [$t(selectedButton.text2)]) }}
          </v-btn>
        </div>
      </div>
    </div>

    <!-- <div
      v-if="showDetailsPanel && !$vuetify.breakpoint.smAndDown"
      class="details-panel"
      :style="{
        bottom: 0,
        left: 0,
        width: '100%',
        height: settings.detailsPanelHeight || '200px',
        background: settings.detailsPanelBgColor || 'white',
        color: settings.detailsPanelTextColor || '#403199',
        padding: settings.detailsPanelPadding || '20px',
        borderTopLeftRadius: settings.detailsPanelBorderRadius || '15px',
        borderTopRightRadius: settings.detailsPanelBorderRadius || '15px',
        transform: showDetailsPanel ? 'translateY(0)' : 'translateY(100%)',
        transition: 'transform 0.3s ease',
      }"
    >
      <div class="panel-content" >
        <div style="font-size: 14px; margin-bottom: 16px">
          {{ $t("button_description", [$t(selectedButton.text)]) }}
        </div>
        <div
          style="
            font-size: 14px;
            line-height: 1.6;
            margin: 0 16px;
            max-height: 420px;
            overflow-y: auto;
          "
          v-html="formatDoc($t(selectedButton.doc) || $t(selectedButton.text))"
        ></div>
        <v-btn
          class="action-button"
          color="#E91E63"
          dark
          @click="createAction"
          style="margin-top: 16px"
        >
          {{ $t("create_button", [$t(selectedButton.text)]) }}
        </v-btn>
        <v-btn
          class="action-button mx-8"
          color="#E91E63"
          dark
          @click="createAction"
          style="margin-top: 16px"
        >
          {{ $t("create_button", [$t(selectedButton.text)]) }}
        </v-btn>
      </div>
    </div>
 -->
    <!-- v-bottom-sheet for mobile -->
    <v-bottom-sheet
      v-model="sheet"
      width="100%"
      v-if="$vuetify.breakpoint.smAndDown && selectedButton.text"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          ref="templatePhone"
          class="nav-button"
          :class="{ active: sheet }"
          v-bind="attrs"
          v-on="on"
        >
          <i class="fa fa-angle-double-up"></i>
          {{ $t("button_details", [$t(selectedButton.text)]) }}
          <i class="fa fa-angle-double-up"></i>
        </v-btn>
        <v-btn
          class="action-button mx-8"
          :color="selectedButton.color1"
          dark
          @click="createAction"
          style="margin-top: 16px"
        >
          {{ $t("create_button", [$t(selectedButton.text)]) }}
        </v-btn>
      </template>
      <v-sheet
        class="sheet-content"
        :style="{
          padding: settings.sheetContentPadding || '25px',
          backgroundColor: settings.detailsPanelBgColor || 'white',
          color: settings.detailsPanelTextColor || 'black',
          fontSize: '13px',
          lineHeight: 2.5,
          overflow: 'auto',
          maxHeight: '100% !important',
        }"
      >
        <v-btn class="mt-6 btn-sheet" @click="sheet = !sheet" icon>
          <i class="fa fa-angle-double-down"></i>
        </v-btn>
        <div class="sheet-text">
          <div class="preview-area" ref="createButton">
            <div class="phone-frame">
              <template v-if="selectedButton.image">
                <img
                  class="inner-image"
                  :src="selectedButton.image"
                  @error="selectedButton.image = ''"
                  alt=""
                />
              </template>
              <template v-else>
                <div class="alt-placeholder">
                  {{ $t("inner_image_alt") }}
                </div>
              </template>
            </div>
            <div
              v-if="selectedButton.text && !$vuetify.breakpoint.smAndDown"
              class="text-area"
            >
              <div class="text-title">
                <span>{{
                  $t("create_qr_code", [$t(selectedButton.text)])
                }}</span>
              </div>
              <div
                class="justified-text"
                v-html="
                  formatDoc($t(selectedButton.doc) || $t(selectedButton.text))
                "
              ></div>
              <v-btn
                class="action-button"
                :color="selectedButton.color1"
                dark
                @click="createAction"
              >
                {{ $t("create_qr_code", [$t(selectedButton.text)]) }}
              </v-btn>
              <v-btn
                class="action-button"
                :color="selectedButton.color2"
                dark
                @click="createAction2"
              >
                {{ $t("create_qr_code2", [$t(selectedButton.text2)]) }}
              </v-btn>
            </div>
          </div>
          <div style="font-size: 14px; margin-bottom: 16px">
            {{ $t("button_details_title", [$t(selectedButton.text)]) }}
          </div>
          <div
            style="
              font-size: 14px;
              line-height: 1.6;
              margin: 0 16px;
              max-height: 60vh;
              overflow-y: auto;
            "
            v-html="
              formatDoc($t(selectedButton.doc) || $t(selectedButton.text))
            "
          ></div>
          <v-btn
            class="action-button"
            :color="selectedButton.color1"
            dark
            @click="createAction"
            style="margin-top: 16px"
          >
            {{ $t("create_button", [$t(selectedButton.text)]) }}
          </v-btn>
        </div>
      </v-sheet>
    </v-bottom-sheet>
  </div>
</template>

<script>
import QrcdrButton from "./QrcdrButton.vue";
import { EventBus } from "./eventBus";

export default {
  name: "DynamicTab",
  props: {
    settings: {
      type: Object,
      required: true,
      default: () => ({
        dynamicButtons: [],
        defaultImage: "https://via.placeholder.com/150",
        defaultDoc: "",
        frameImage: "img/cell.png",
      }),
    },
  },
  components: { QrcdrButton },
  data() {
    return {
      frameImage: "img/cell.png",
      dynamicButtons: [],
      showDetailsPanel: false,
      selectedButton: {},
      sheet: false,
      showAllDynamicButtons: false,
    };
  },
  computed: {
    isMobileBreakpoint() {
      // Enable show all button for 768px to 960px as well
      return (
        this.$vuetify.breakpoint.smAndDown ||
        (this.$vuetify.breakpoint.md && this.$vuetify.breakpoint.width <= 960)
      );
    },
    isXsBreakpoint() {
      return this.$vuetify.breakpoint.xs;
    },
    visibleDynamicButtons() {
      if (!this.dynamicButtons || !Array.isArray(this.dynamicButtons)) {
        console.warn("dynamicButtons is not defined or not an array");
        return [];
      }
      console.log(
        "isXsBreakpoint:",
        this.isXsBreakpoint,
        "showAllDynamicButtons:",
        this.showAllDynamicButtons,
        "dynamicButtons:",
        this.dynamicButtons
      );
      if (!this.showAllDynamicButtons && this.isMobileBreakpoint) {
        if (this.isXsBreakpoint) return this.dynamicButtons.slice(0, 4);
        else return this.dynamicButtons.slice(0, 6);
      }
      return this.dynamicButtons;
    },
  },
  watch: {
    "settings.dynamicButtons": {
      handler(newButtons) {
        this.dynamicButtons = newButtons.map((button) => ({
          ...button,
          image:
            button.image ||
            this.settings.defaultImage ||
            "https://via.placeholder.com/150",
          doc: button.doc || this.settings.defaultDoc || this.$t("defaultDoc"),
          frameImage: button.frameImage || this.settings.frameImage,
          subtext: button.subtext || this.$t("defaultSubtext"),
          link1: button.link1 || "",
          link2: button.link2 || "#",
        }));
      },
      immediate: true,
      deep: true,
    },
    "$vuetify.breakpoint.smOnly"(isMobile) {
      if (!isMobile) {
        this.sheet = false;
        this.showDetailsPanel = true;
      }
    },
    "$vuetify.breakpoint.xsOnly"(isMobile) {
      if (!isMobile) {
        this.sheet = false;
        this.showDetailsPanel = true;
      }
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
    toggleShowAll() {
      this.showAllDynamicButtons = !this.showAllDynamicButtons;
      console.log(
        "Toggled showAllDynamicButtons to:",
        this.showAllDynamicButtons
      );
    },
    showDetails(button) {
      this.selectedButton = button;
      this.showDetailsPanel = false;
      EventBus.$emit("updateTopTitle", this.selectedButton.text);

      if (this.$vuetify.breakpoint.smAndDown) {
        this.sheet = true; // Open v-bottom-sheet for mobile
        this.showDetailsPanel = true; // If details-panel is also shown

        this.$nextTick(() => {
          const image = this.$refs.templatePhone;
          if (image) {
            // Get phone-frame position
            const rect = image.getBoundingClientRect();
            const scrollTop =
              window.pageYOffset || document.documentElement.scrollTop;
            const targetY = rect.top + scrollTop + rect.height + 10; // 10 pixels below the end of phone-frame
            window.scrollTo({
              top: targetY,
              behavior: "smooth",
            });
          }
        });
      } else {
        this.showDetailsPanel = true;
        const createButton = this.$refs.createButton;
        if (createButton) {
          createButton.scrollIntoView({
            behavior: "smooth",
            block: "center",
            inline: "nearest",
          });
        }
      }
    },
    formatDoc(doc) {
      if (!doc) return "";
      return doc.replace(/\n/g, "<br>").replace(/\n+/g, " ").trim();
    },
    createAction() {
      if (this.selectedButton.link1) {
        window.location.href = this.selectedButton.link1;
      }
    },
    createAction2() {
      if (this.selectedButton.link2) {
        this.$router.push({
          path: this.selectedButton.link2,
        });
      }
    },
  },
};
</script>

<style scoped>
/* Existing styles remain unchanged */
.dynamic-tab {
  text-align: center;
  padding: 20px;
}

.content-wrapper {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 30px;
  align-items: flex-start;
}

.dynamic-buttons-grid {
  width: 100%;
  padding: 20px;
}

.button-col {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
}

.show-all-button-col {
  flex: 0 0 auto; /* Prevent shrinking */
  width: 100%; /* Take full width */
  max-width: 100%;
  margin-right: 0%;
  margin-left: 0%;
}

.preview-area {
  width: 100%;
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 45px;
  direction: rtl;
}

.text-area {
  width: 75%;
  text-align: right;
  color: #333;
  font-size: 14px;
  line-height: 1.6;
  margin: 0 20px;
}

.phone-frame {
  width: 250px;
  height: 500px;
  background: transparent;
  border-radius: 45px;
  margin: 0;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
}

.details-panel {
  z-index: 100;
}

.panel-content {
  text-align: center;
  justify-content: flex-end;
  direction: rtl;
  font-size: 13px;
  line-height: 2.5;
}

.bottom-nav {
  z-index: 1000;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #403199 !important;
  height: 45px;
  display: flex;
  justify-self: center;
  align-items: center;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
  text-align: center;
}

.nav-button {
  margin-top: 20px;
  background-color: #403199 !important;
  border-radius: 12px;
  padding: 0 16px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 14px;
  justify-self: center;
  text-align: center;
  transition: transform 0.3s ease, background 0.3s ease;
  gap: 8px;
}

.nav-button i {
  font-size: 16px;
  color: white;
  display: inline-block;
  transition: transform 0.3s ease;
}

.nav-button.active {
  transform: scale(1.05);
  background-color: #5e4db2;
}

.nav-button.active i {
  transform: rotate(180deg);
}

/* RTL support for navigation button */
[dir="rtl"] .nav-button {
  flex-direction: row-reverse;
}

[dir="rtl"] .nav-button i {
  transform: scaleX(-1);
}

[dir="rtl"] .nav-button.active i {
  transform: scaleX(-1) rotate(180deg);
}

.sheet-content {
  background-color: white !important;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
  height: 100% !important;
  width: 100% !important;
}

:deep(.v-bottom-sheet.v-dialog) {
  height: 100% !important;
  width: 99% !important;
  max-height: 99.9% !important;
}

.sheet-text {
  text-align: right;
  direction: rtl;
  font-size: 13px;
  line-height: 2.5;
  padding-top: 100px !important;
  padding-left: 20px !important;
  padding-right: 20px !important;
}

.action-button {
  border-radius: 8px;
  font-size: 14px;
  padding: 8px 16px;
  margin-top: 40px;
}

.inner-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 240px;
  height: 480px;
  object-fit: cover;
  border-radius: 15px;
}

.phoneFrame {
  width: 100%;
  height: 100%;
  position: relative;
  z-index: 2;
}

.justified-text {
  text-align: justify;
  line-height: 2.5;
  font-size: 13px;
  direction: rtl;
}

.text-title {
  border-radius: 15px;
  background-color: v-bind("settings.topBgColor");
  margin-bottom: 45px;
  width: max-content;
  padding: 15px;
}
[dir="rtl"] .btn-sheet .fa {
  background-image: linear-gradient(to top, #403199, #e9354d); /* معکوس */
}
.btn-sheet {
  background: transparent !important;
  box-shadow: none !important;
  position: absolute;
  overflow: visible;
  width: 60px;
  height: 60px;
  top: 10px;
  left: 50%;
  transform: translateX(-50%);
}
.btn-sheet .fa {
  font-size: 43px !important;
  color: transparent !important;
  -webkit-background-clip: text;
  background-clip: text;
  background-image: linear-gradient(to top, #e9354d, #403199);
  transition: all 0.3s ease;
  display: inline-block;
}

.btn-sheet:hover .fa,
.btn-sheet.active .fa {
  font-size: 46px !important;
  background-image: linear-gradient(to bottom, #e9354d, #403199);
  -webkit-background-clip: text;
  background-clip: text;
  animation: gradientAnimation 1.5s ease infinite;
}

/* RTL support for sheet button */
/* [dir="rtl"] .btn-sheet .fa {
  font-size: 43px !important;
  color: transparent !important;
  -webkit-background-clip: text;
  background-clip: text;
  background-image: linear-gradient(to top, #e9354d, #403199);
  transition: all 0.3s ease;
  display: inline-block; */
/* transform: scaleX(-1); */
/* }

[dir="rtl"] .btn-sheet:hover .fa,
[dir="rtl"] .btn-sheet.active .fa {
  transform: scaleX(-1);
} */

@keyframes gradientAnimation {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .content-wrapper {
    flex-direction: column;
  }

  .preview-area {
    display: flex;
    flex-direction: column;
    width: 100%;
    justify-content: center;
    align-items: center;
  }

  .phone-frame {
    width: 250px;
    height: 500px;
    background: transparent;
    border-radius: 45px;
  }

  .text-area {
    display: none;
  }

  .dynamic-buttons-grid {
    padding-right: 20px;
    padding-left: 20px;
    padding-bottom: 20px;
  }

  .button-col {
    padding-right: 40px;
    padding-left: 40px;
    padding-bottom: 40px;
  }

  ::deep(.v-row) {
    gap: 40px !important;
    row-gap: 40px !important;
    margin: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
  }

  ::deep(.v-col) {
    padding: 0 !important;
  }
}

/* 430px and below: 2 columns with 31px gap */
@media (max-width: 430px) {
  .dynamic-tab {
    padding-right: 0px;
    padding-left: 0px;
    row-gap: 31px;
  }

  .dynamic-buttons-grid {
    gap: 31px;
    padding-right: 0px;
  }

  ::deep(.v-row) {
    gap: 31px !important;
    row-gap: 31px !important;
    margin: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
  }

  ::deep(.v-col) {
    padding: 0 !important;
  }
}

@media (max-width: 425px) {
  .dynamic-tab {
    padding-right: 0px;
    padding-left: 0px;
    row-gap: 31px !important;
  }

  .dynamic-buttons-grid {
    gap: 31px !important;
    padding-right: 0px;
  }

  ::deep(.v-row) {
    gap: 31px !important;
    row-gap: 31px !important;
    margin: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
  }

  ::deep(.v-col) {
    padding: 0 !important;
  }
}

/* Very small mobile devices (344px and below) */
@media (max-width: 344px) {
  .dynamic-tab {
    padding-right: 0px;
    padding-left: 0px;
    padding-top: 0px !important;
    row-gap: 31px !important;
  }

  .dynamic-buttons-grid {
    gap: 31px !important;
    padding-right: 0px;
    padding-left: 0px;
  }

  .button-col {
    padding: 25px !important;
  }
  .button-col-showall {
    padding: 8px !important;
  }

  ::deep(.v-row) {
    gap: 31px !important;
    row-gap: 31px !important;
    margin: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
  }

  ::deep(.v-col) {
    padding: 0 !important;
  }
}

/* Desktop gaps: column = 98px, row = 88px */
@media (min-width: 1024px) {
  .dynamic-buttons-grid {
    padding: 8px;
  }

  .button-col {
    padding: 25px;
  }

  ::deep(.v-row) {
    gap: 98px !important;
    row-gap: 88px !important;
    margin: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
  }

  ::deep(.v-col) {
    padding: 0 !important;
  }
}

/* Tablet gaps: column = 98px, row = 88px */
@media (min-width: 769px) and (max-width: 1023px) {
  .dynamic-buttons-grid {
    padding: 20px;
  }

  .button-col {
    padding: 10px;
  }

  ::deep(.v-row) {
    gap: 98px !important;
    row-gap: 88px !important;
    margin: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
  }

  ::deep(.v-col) {
    padding: 0 !important;
  }
}

/* Vuetify grid responsive overrides */
::deep(.v-row) {
  margin: 0 !important;
  display: flex !important;
  flex-wrap: wrap !important;
  gap: 0 !important;
  row-gap: 0 !important;
}

::deep(.v-col) {
  padding: 0 !important;
  margin: 0 !important;
}

::deep(.v-col .snake-border-button) {
  width: 100% !important;
  max-width: 100% !important;
}

::deep(.dynamic-buttons-grid .v-row) {
  margin: 0 !important;
  display: flex !important;
  flex-wrap: wrap !important;
}

:deep(.dynamic-buttons-grid .v-col) {
  padding: 0 !important;
  margin: 0 !important;
}

@media (max-width: 768px) {
  ::deep(.dynamic-buttons-grid .v-row) {
    gap: 40px !important;
    row-gap: 40px !important;
  }
}

@media (max-width: 430px) {
  ::deep(.dynamic-buttons-grid .v-row) {
    gap: 31px !important;
    row-gap: 31px !important;
  }
}

@media (max-width: 425px) {
  ::deep(.dynamic-buttons-grid .v-row) {
    gap: 31px !important;
    row-gap: 31px !important;
  }
}

@media (max-width: 344px) {
  ::deep(.dynamic-buttons-grid .v-row) {
    gap: 31px !important;
    row-gap: 31px !important;
  }
}

@media (min-width: 769px) {
  ::deep(.dynamic-buttons-grid .v-row) {
    gap: 98px !important;
    row-gap: 88px !important;
  }
}

/* Show All Button specific styles */
.snake-border-button.show-all-button {
  flex-direction: row !important;
  justify-content: space-between !important;
  align-items: center !important;
  width: 100% !important;
  max-width: 100% !important;
}

.snake-border-button.show-all-button .show-all-icon {
  order: 2; /* آیکون بعد از متن */
}

.snake-border-button.show-all-button .show-all-text {
  order: 1; /* متن قبل از آیکون */
  flex: 1;
  text-align: center !important;
}

.snake-border-button.show-all-button .line1 {
  text-align: center !important;
  width: 100% !important;
  min-width: auto !important;
}

/* Tablet responsive for show all button (768px تا 960px) */
@media (min-width: 768px) and (max-width: 960px) {
  .snake-border-button.show-all-button {
    width: 100% !important;
    max-width: 100% !important;
    flex-direction: row !important;
    justify-content: space-between !important;
    align-items: center !important;
  }

  .snake-border-button.show-all-button .show-all-icon {
    order: 2 !important;
    margin-left: 15px !important;
  }

  .snake-border-button.show-all-button .show-all-text {
    order: 1 !important;
  }
}
.alt-placeholder {
  width: 250px;
  height: 500px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  font-size: 16px;
  font-weight: 500;
  font-family: inherit;
  color: var(primary);
  background-color: #f8f8f8;
  border-radius: 5px;
  padding: 10px;
}
</style>
