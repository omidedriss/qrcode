<template>
  <div class="nav-container">
    <v-tabs
      v-if="settings.visibleTabs"
      v-model="activeTab"
      class="nav-tabs"
      :style="{
        backgroundColor: settings.tabBgColor,
        '--active-tab-color': settings.activeTabColor,
      }"
    >
      <v-tab
        class="navTab"
        :style="{
          fontSize:
            activeTab === 0 || activeTab === `0`
              ? settings.tabTextActiveSize
              : settings.tabTextSize,
        }"
        @click="updateTopTitle(settings.staticTabText)"
      >
        <component :is="settings.selectedTagTab" class="navTab">{{
          $t(settings.staticTabText)
        }}</component>
      </v-tab>
      <v-tab
        class="navTab"
        :style="{
          fontSize:
            activeTab === 1 || activeTab === `1`
              ? settings.tabTextActiveSize
              : settings.tabTextSize,
        }"
        @click="updateTopTitle(settings.dynamicTabText)"
        >{{ $t(settings.dynamicTabText) }}</v-tab
      >
      <v-tabs-items v-model="activeTab">
        <keep-alive>
          <v-tab-item>
            <slot name="static"></slot>
          </v-tab-item>
        </keep-alive>
        <keep-alive>
          <v-tab-item>
            <slot name="dynamic"></slot>
          </v-tab-item>
        </keep-alive>
      </v-tabs-items>
    </v-tabs>
  </div>
</template>

<script>
import { EventBus } from "./eventBus";
export default {
  name: "NavTabs",
  props: {
    settings: {
      type: Object,
      required: true,
    },
    modelValue: {
      type: [Number, String],
      default: 0,
    },
    value: [Number, String],
  },
  data() {
    return {
      activeTab: Number(this.modelValue) || 0,
      tabs: [
        { title: "online_qr_title", slot: "static" },
        { title: "dynamic_qr_title", slot: "dynamic" },
      ],
    };
  },
  methods: {
    updateTopTitle(title) {
      EventBus.$emit("updateTopTitle", title);
    },
  },
  computed: {
    activeModel: {
      get() {
        return this.value;
      },
      set(newVal) {
        this.$emit("input", newVal);
      },
    },
  },
  mounted() {
    console.log("Initial activeTab:", this.activeTab);
  },
  watch: {
    activeTab(newVal) {
      this.$emit("update:modelValue", newVal);
      console.log("Active tab changed to:", newVal);
    },
    modelValue(newVal) {
      console.log("Model value updated to:", newVal);
      this.activeTab = newVal;
    },
  },
};
</script>

<style scoped>
:deep(.v-tabs-bar) {
  height: 91px !important;
}
* {
  box-sizing: border-box !important;
}
:deep(.v-tabs-slider-wrapper) {
  bottom: -10px !important;
}
.nav-container {
  width: 95%;
  margin: 20px;
  display: flex;
  justify-content: center;
}
.navTab {
  width: 95% !important;
  margin: 0px;
  max-width: 85% !important;
  padding: auto;
  display: flex;
  height: 73px !important;
  overflow: visible !important;
}
.nav-tabs {
  width: 90% !important;
  min-width: 85% !important;
  margin-bottom: 20px;
  display: block;
  justify-content: center;
  overflow: visible !important;
  padding: 10px;
  max-width: 90%;
  height: 73px !important;
  align-items: center !important;
  font-size: 13px !important;
}
:deep(.v-tab) {
  margin-left: 20px;
  margin-right: 20px;
  font-weight: bold;
  width: 70% !important;
  overflow: visible !important;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1), 0px -4px 15px rgba(0, 0, 0, 0.1);
  text-align: center;
  justify-content: center;
  transition: all 0.3s ease;
  padding-top: 5px;
  padding-bottom: 5px;
  align-items: center !important;
  font-size: 13px !important;
  height: 52px;
  min-height: 52px;
  display: flex !important;
}
:deep(.v-tab *) {
  font-size: 13px !important;
  align-items: center !important;
  justify-self: center;
  justify-content: center;
}
:deep(.v-slide-group__wrapper) {
  padding-top: 10px !important;
  padding-bottom: 10px !important;
}
:deep(.v-tab--active),
:deep(.v-tab:hover) {
  background-color: var(--active-tab-color);
  color: white !important;
  box-shadow: 0px 6px 15px #41319917, 0px -6px 15px #41319917;
  overflow: visible !important;
}
.v-tabs-bar {
  height: 71px !important;
}

/* Tablet and smaller desktop */
@media (max-width: 1024px) {
  .nav-container {
    width: 95% !important;
    max-width: 95% !important;
  }
  .nav-tabs {
    width: 95% !important;
    max-width: 95% !important;
    min-width: 95% !important;
    font-size: 12px !important;
    height: 60px !important;
  }
  .navTab {
    height: 60px !important;
  }
  :deep(.v-tab) {
    font-size: 12px !important;
    height: 46px;
    min-height: 46px;
    display: flex !important;
  }
  :deep(.v-tabs-bar) {
    height: 60px !important;
  }
  :deep(.v-slide-group__wrapper) {
    height: auto !important;
    width: 100% !important;
  }
}

/* Tablet */
@media (max-width: 768px) {
  .v-window__container {
    width: 100%;
  }
  .nav-container {
    width: 99% !important;
    max-width: 99% !important;
    min-height: 120px;
    height: auto;
    margin-top: 100px !important;
  }
  :deep(.v-slide-group__content) {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    align-items: center;
  }
  :deep(.v-item-group),
  :deep(.v-slide-group__content),
  .v-slide-group__content {
    width: 100% !important;
    max-width: 100% !important;
  }
  .v-item-group {
    margin-top: 25px;
  }

  :deep(.v-slide-group__wrapper) {
    height: 120px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
  }
  .nav-tabs {
    width: 99% !important;
    max-width: 99% !important;
    min-width: 99% !important;
    display: flex;
    flex-direction: column !important;
    align-items: center !important;
    font-size: 13px !important;
    height: 120px !important;
    min-height: 120px;
  }
  :deep(.v-tab) {
    width: 85% !important;
    max-width: 85%;
    margin-left: 0;
    margin-right: 0;
    margin-bottom: 10px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    height: 52px;
    min-height: 52px;
    padding: 5px;
    font-size: 13px !important;
    display: flex !important;
  }
  .navTab {
    height: 52px !important;
  }
  :deep(.v-tabs-bar) {
    height: 120px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
  }
  :deep(.v-tabs-items) {
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important;
    min-height: 200px;
    overflow: visible !important;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
  }
  :deep(.v-tab-item) {
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important;
    min-height: 150px;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    overflow: visible !important;
  }
  :deep(.v-tab-item > *) {
    width: 100% !important;
    max-width: 100% !important;
    height: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 5px;
  }
}

/* Mobile */
@media (max-width: 480px) {
  .nav-container {
    width: 98% !important;
    max-width: 98% !important;
    min-height: 100px;
    margin-top: 80px !important;
  }
  .nav-tabs {
    width: 98% !important;
    max-width: 98% !important;
    min-width: 98% !important;
    font-size: 12px !important;
    min-height: 50px;
    height: 100px !important;
  }
  :deep(.v-tab) {
    height: 46px;
    min-height: 46px;
    font-size: 12px !important;
    padding: 3px;
    display: flex !important;
    width: 85% !important;
    max-width: 85%;
    margin-bottom: 8px;
  }
  :deep(.v-tabs-bar) {
    height: 100px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
  }
  :deep(.v-tabs-items) {
    min-height: 150px;
    padding: 8px;
  }
  :deep(.v-tab-item) {
    min-height: 120px;
    padding: 8px;
  }
  .navTab {
    height: 46px !important;
  }
}

/* Small mobile */
@media (max-width: 360px) {
  .nav-container {
    width: 97% !important;
    max-width: 97% !important;
    min-height: 90px;
    margin-top: 70px !important;
  }
  .nav-tabs {
    width: 97% !important;
    max-width: 97% !important;
    min-width: 97% !important;
    font-size: 11px !important;
    min-height: 60px;
    height: 90px !important;
  }
  :deep(.v-tab) {
    height: 39px;
    min-height: 39px;
    font-size: 11px !important;
    padding: 2px;
    display: flex !important;
    width: 90% !important;
    max-width: 90%;
    margin-bottom: 6px;
  }
  :deep(.v-tabs-bar) {
    height: 90px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
  }
  :deep(.v-tabs-items) {
    min-height: 120px;
    padding: 5px;
  }
  :deep(.v-tab-item) {
    min-height: 100px;
    padding: 5px;
  }
  .navTab {
    height: 39px !important;
  }
}
</style>
