<template>
  <div
    v-if="isBitcoinEnabled"
    class="tab-pane fade"
    :class="{ 'show active': activeSection === '#bitcoin' }"
    id="paypal"
  >
    <v-autocomplete v-model="form.btc_typePay"
      >{{ $t("paypal") }}
    </v-autocomplete>
    <div class="col-sm-6">
      <v-text-field type="email" v-model="form.btc_email">{{
        $t("amount")
      }}</v-text-field>
    </div>
    <div class="col-sm-6">
      <v-text-field v-model="form.btc_name_item" type="text">{{
        $t("item_name")
      }}</v-text-field>
    </div>
    <div class="col-sm-6">
      <v-text-field v-model="form.btc_id_item" type="text">{{
        $t("iditem")
      }}</v-text-field>
    </div>
    <div class="col-sm-6">
      <v-text-field type="number">{{ $t("price") }}</v-text-field>
    </div>
  </div>
</template>

<script>
export default {
  name: "PyPalTab",
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
      itemData: {},
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
