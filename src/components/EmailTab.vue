<template>
  <v-container class="email-tab fadeIn" v-if="isActive" id="email">
    <p class="rtl-label tag">{{ $t("buttons.email") }}</p>
    <v-row>
      <v-col cols="12" md="12" class="pa-0 pt-5">
        <v-text-field
          v-model="form.mailto"
          :label="$t('send_to')"
          type="email"
          placeholder="E-Mail"
          required
          outlined
          dense
          class="link-field"
          :rules="emailRules"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="12" class="pa-0">
        <v-text-field
          v-model="form.subject"
          :label="$t('subject')"
          required
          outlined
          dense
          :rules="requiredRules"
        ></v-text-field>
      </v-col>

      <v-col cols="12" class="pa-0">
        <v-textarea
          v-model="form.body"
          :label="$t('text')"
          required
          outlined
          dense
          :rules="bodyRules"
          maxlength="1000"
          counter
        ></v-textarea>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  name: "EmailTab",
  props: {
    isEmailEnabled: {
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
        mailto: "",
        subject: "",
        body: "",
      },
      translations: {
        email: this.$t("email"),
        send_to: this.$t("send_to"),
        subject: this.$t("subject"),
        text: this.$t("text"),
      },
      emailRules: [
        (v) => !!v || this.$t("requiredEmail"),
        (v) => /.+@.+\..+/.test(v) || this.$t("validEmail"),
      ],
      requiredRules: [(v) => !!v || this.$t("requiredRules")],
      bodyRules: [
        (v) => !!v || this.$t("requiredMessage"),
        (v) => (v && v.length <= 1000) || this.$t("EmailLength"),
      ],
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#email";
    },
  },
  methods: {},
};
</script>

<style scoped>
.email-tab {
  padding: 20px;
}

.v-text-field,
.v-textarea {
  margin-bottom: 0px;
}
</style>
