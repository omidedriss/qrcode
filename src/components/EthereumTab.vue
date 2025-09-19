<template>
  <v-container
    v-if="getsection === '#ethereum'"
    class="ethereum-tab fadeIn"
    id="ethereum"
  >
    <p class="rtl-label tag">{{ $t("buttons.ethereum") }}</p>
    <v-row class="form-group">
      <v-col cols="12" md="6" class="pa-0 pr-3">
        <v-text-field
          v-model="form.ether_account"
          :label="$t('address')"
          class="ltr"
          required
          outlined
          dense
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="6" class="pa-0 pr-3 etherMount">
        <v-text-field
          v-model="form.ether_amount"
          :label="$t('amount')"
          type="number"
          outlined
          dense
          step="0.000000000000000001"
          append-icon="mdi-ethereum"
          @input="restrictDecimals"
        >
        </v-text-field>
        <!-- <div class="ether-rates">{{ getetherRates }}</div> -->
      </v-col>

      <v-col cols="12" md="6" class="pa-0 pr-3">
        <v-text-field
          v-model="form.ether_label"
          :label="$t('item_name')"
          outlined
          dense
        ></v-text-field>
      </v-col>

      <v-col cols="12" sm="12" class="pa-0 pr-3">
        <v-textarea
          v-model="form.ether_message"
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
  name: "EthereumTab",
  props: {
    isethereumEnabled: {
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
        ether_account: "",
        ether_amount: null,
        ether_label: "",
        ether_message: "",
      },
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#ethereum";
    },
    /* 
   getetherRates() {
      return "Current ether Rate: $50,000";
    },
    */
  },
  methods: {
    restrictDecimals(value) {
      if (value) {
        this.form.ether_amount = parseFloat(value).toFixed(18);
      }
    },
  },
};
</script>

<style scoped>
.ethereum-tab {
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

.ether-rates {
  font-size: 12px;
  color: #666;
  margin-top: 8px;
}

.ltr {
  direction: ltr !important;
}
.etherMount {
  display: flex;
  flex-direction: column;
}
.w100 {
  width: 100% !important;
  max-width: 100% !important;
}
</style>
