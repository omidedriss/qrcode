<template>
  <div
    v-if="isBitcoinEnabled"
    class="tab-pane fade"
    :class="{ 'show active': activeSection === '#bitcoin' }"
    id="bitcoin"
  >
    <div>{{ $t("buttons.bitcoin") }}</div>
    <div class="row form-group">
      <div class="col-sm-6 pa-0 pr-3">
        <v-text-field
          v-model="form.btc_address"
          prepend-icon="fa fa-fax"
          dnese
          :label="$t('address')"
        ></v-text-field>
      </div>
      <div class="col-sm-6 pa-0 pr-3">
        <v-text-field
          type="number"
          v-model="form.btc_amount"
          dense
          append-icon="fa fa-bitcon"
          :label="$t('amount')"
        ></v-text-field>

        <!-- <span class="input-group-text rounded-0">BTC</span>
        </div>
        <div v-html="btcRates"></div> -->
      </div>
      <div class="col-sm-6 pa-0 pr-3">
        <v-text-field
          v-model="form.btc_label"
          type="text"
          dense
          :label="$t('item_name')"
        ></v-text-field>
      </div>
      <div class="col-sm-12 pa-0 pr-3">
        <v-text-field
          v-model="form.btc_label"
          type="text"
          dense
          :label="$t('message')"
        ></v-text-field>

        <!-- <label>{{ $t("message") }}</label>
        <input type="text" v-model="form.btc_message" class="form-control" /> -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "BitcoinTab",
  props: {
    isBitcoinEnabled: {
      type: Boolean,
      default: false,
    },
    activeSection: {
      type: String,
      default: "",
    },
    btcRates: {
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
};
</script>

<style scoped>
.tab-pane {
  padding: 20px;
}
.form-group {
  margin-bottom: 1rem;
}
.form-control {
  width: 100%;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}
.input-group {
  display: flex;
  align-items: center;
}
.input-group-text {
  padding: 0.375rem 0.75rem;
  background-color: #f8f9fa;
  border: 1px solid #ced4da;
  border-left: 0;
}
.ltr {
  direction: ltr;
}
</style>
