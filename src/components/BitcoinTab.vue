<template>
  <v-container v-if="isActive" class="bitcoin-tab fade" id="bitcoin">
    <p class="rtl-label tag">{{ $t("buttons.bitcoin") }}</p>
    <v-row class="form-group">
      <v-col cols="12" md="6" class="pa-0 pr-3">
        <v-text-field
          v-model="form.btc_account"
          :label="$t('address')"
          class="ltr"
          required
          outlined
          dense
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="6" class="pa-0 pr-3 btcMount">
        <v-text-field
          v-model="form.btc_amount"
          :label="$t('amount')"
          type="number"
          outlined
          dense
          append-icon="mdi-bitcoin"
          step="0.00000001"
          @input="restrictDecimals"
        >
        </v-text-field>
        <!-- <div class="btc-rates">{{ getBtcRates }}</div> -->
      </v-col>

      <v-col cols="12" md="6" class="pa-0 pr-3">
        <v-text-field
          v-model="form.btc_label"
          :label="$t('item_name')"
          outlined
          dense
        ></v-text-field>
      </v-col>

      <v-col cols="12" sm="12" class="pa-0 pr-3">
        <v-textarea
          v-model="form.btc_message"
          :label="$t('message')"
          outlined
          dense
          rows="3"
        ></v-textarea>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "BitcoinTab",
  props: {
    isBitcoinEnabled: {
      type: Boolean,
      default: true,
    },
    getsection: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      form: {
        btc_account: "",
        btc_amount: null,
        btc_label: "",
        btc_message: "",
      },
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#bitcoin";
    },
    // getBtcRates() {
    //   return "Current BTC Rate: $50,000";
    // },
  },
  methods: {
    restrictDecimals(value) {
      if (value) {
        this.form.ether_amount = parseFloat(value).toFixed(8);
      }
    },
  },
};
</script>

<style scoped>
.bitcoin-tab {
  padding: 20px;
}

.form-group {
  margin-top: 20px;
}

.input-group-text {
  padding: 8px;
  background-color: #f5f5f5;
  border: 1px solid #ccc;
  border-radius: 0 4px 4px 0;
  font-size: 14px;
}

.btc-rates {
  font-size: 12px;
  color: #666;
  margin-top: 8px;
}

.ltr {
  direction: ltr !important;
}
.btcMount {
  display: flex;
  flex-direction: column;
}
</style>
