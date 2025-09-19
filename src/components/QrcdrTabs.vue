<template>
  <v-container fluid>
    <v-row>
      <!-- Navigation Tabs -->
      <v-col
        :cols="$vuetify.breakpoint.smAndUp && layout === 'vertical' ? 4 : 12"
        :md="layout === 'vertical' ? 3 : 12"
        :class="{ 'order-last': layout === 'vertical' && sidebar === 'left' }"
      >
        <v-tabs
          v-model="activeTab"
          :vertical="layout === 'vertical' && $vuetify.breakpoint.smAndUp"
          :class="{ 'text-sm-start': layout === 'vertical' }"
          background-color="transparent"
          grow
        >
          <v-tab
            v-if="config.link"
            :href="'#link'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-link</v-icon>
            <span class="d-inline-block">{{ $t("link") }}</span>
          </v-tab>
          <v-tab
            v-if="config.text"
            :href="'#text'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-format-align-left</v-icon>
            <span class="d-inline-block">{{ $t("text") }}</span>
          </v-tab>
          <v-tab
            v-if="config.email"
            :href="'#email'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-email-outline</v-icon>
            <span class="d-inline-block">{{ $t("email") }}</span>
          </v-tab>
          <v-tab
            v-if="config.location"
            :href="'#location'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-map-marker</v-icon>
            <span class="d-inline-block">{{ $t("location") }}</span>
          </v-tab>
          <v-tab
            v-if="config.tel"
            :href="'#tel'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-phone</v-icon>
            <span class="d-inline-block">{{ $t("phone") }}</span>
          </v-tab>
          <v-tab
            v-if="config.sms"
            :href="'#sms'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-cellphone</v-icon>
            <span class="d-inline-block">{{ $t("sms") }}</span>
          </v-tab>
          <v-tab
            v-if="config.whatsapp"
            :href="'#whatsapp'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-whatsapp</v-icon>
            <span class="d-inline-block">WhatsApp</span>
          </v-tab>

          <v-tab
            v-if="config.zoom"
            :href="'#zoom'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-video</v-icon>
            <span class="d-inline-block">Zoom</span>
          </v-tab>
          <v-tab
            v-if="config.wifi"
            :href="'#wifi'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-wifi</v-icon>
            <span class="d-inline-block">{{ $t("wifi") }}</span>
          </v-tab>
          <v-tab
            v-if="config.vcard"
            :href="'#vcard'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-card-text-outline</v-icon>
            <span class="d-inline-block">{{ $t("vcard") }}</span>
          </v-tab>
          <v-tab
            v-if="config.event"
            :href="'#event'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-calendar-outline</v-icon>
            <span class="d-inline-block">{{ $t("event") }}</span>
          </v-tab>
          <v-tab
            v-if="config.paypal"
            :href="'#paypal'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-paypal</v-icon>
            <span class="d-inline-block">{{ $t("paypal") }}</span>
          </v-tab>
          <v-tab
            v-if="config.bitcoin"
            :href="'#bitcoin'"
            :class="{ rounded: roundedBtnNav }"
          >
            <v-icon left>mdi-bitcoin</v-icon>
            <span class="d-inline-block">{{ $t("bitcoin") }}</span>
          </v-tab>
        </v-tabs>
      </v-col>

      <!-- Content Area -->
      <v-col
        :cols="$vuetify.breakpoint.smAndUp && layout === 'vertical' ? 8 : 12"
        :md="layout === 'vertical' ? 9 : 12"
      >
        <v-tabs-items v-model="activeTab">
          <v-tab-item v-if="config.link" value="link">
            <slot name="link"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.text" value="text">
            <slot name="text"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.email" value="email">
            <slot name="email"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.location" value="location">
            <slot name="location"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.tel" value="tel">
            <slot name="tel"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.sms" value="sms">
            <slot name="sms"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.whatsapp" value="whatsapp">
            <slot name="whatsapp"></slot>
          </v-tab-item>

          <v-tab-item v-if="config.zoom" value="zoom">
            <slot name="zoom"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.wifi" value="wifi">
            <slot name="wifi"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.vcard" value="vcard">
            <slot name="vcard"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.event" value="event">
            <slot name="event"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.paypal" value="paypal">
            <slot name="paypal"></slot>
          </v-tab-item>
          <v-tab-item v-if="config.bitcoin" value="bitcoin">
            <slot name="bitcoin"></slot>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "QrcdrTabs",
  props: {
    getsection: {
      type: String,
      default: "",
    },
    layout: {
      type: String,
      default: "horizontal",
    },
    sidebar: {
      type: String,
      default: "right",
    },
    config: {
      type: Object,
      default: () => ({
        link: false,
        text: false,
        email: false,
        location: false,
        tel: false,
        sms: false,
        whatsapp: false,
        zoom: false,
        wifi: false,
        vcard: false,
        event: false,
        paypal: false,
        bitcoin: false,
      }),
    },
    roundedBtnNav: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      activeTab: this.getsection.replace("#", "") || "link",
    };
  },
  watch: {
    getsection(newVal) {
      this.activeTab = newVal.replace("#", "") || "link";
    },
  },
};
</script>

<style scoped>
.v-tabs {
  position: sticky;
  top: 0;
  z-index: 1;
}
.rounded {
  border-radius: 4px;
}
</style>
