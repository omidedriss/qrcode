<template>
  <v-container class="text-tab fadeIn" v-if="getsection === '#text'" id="text">
    <p class="rtl-label tag">{{ $t("text") }}</p>

    <v-form ref="textTab" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12">
          <v-textarea
            v-model="form.data"
            :label="$t('message')"
            :rules="[
              (v) => !!v || $t('field_required'),
              (v) => v.length <= 1000 || $t('max_1000_chars'),
            ]"
            rows="3"
            counter="1000"
            required
            outlined
          ></v-textarea>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
export default {
  name: "TextTab",
  props: {
    getsection: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      valid: true,
      form: {
        data: "",
      },
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#text" ? "show active" : "";
    },
  },
  methods: {
    validate() {
      return this.$refs.textForm.validate();
    },
  },
};
</script>

<style scoped>
.v-card {
  padding: 16px;
}
</style>
