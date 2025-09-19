<template>
  <v-container
    class="event-tab fadeIn"
    v-if="getsection === '#event'"
    id="event"
  >
    <!-- <v-card class="pa-4"> -->
    <p class="rtl-label tag">{{ $t("buttons.event") }}</p>
    <v-form ref="eventTab" v-model="valid" lazy-validation>
      <v-row>
        <v-col md="6" sm="12" cols="12" class="pa-0 pr-3">
          <v-text-field
            v-model="form.eventtitle"
            :label="$t('event_title')"
            :rules="[(v) => !!v || $t('field_required')]"
            required
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col md="6" sm="12" cols="12" class="pa-0 pr-3">
          <v-text-field
            v-model="form.eventlocation"
            :label="$t('location')"
            outlined
            dense
          ></v-text-field>
        </v-col>

        <v-col md="6" sm="12" cols="12" class="pa-0 pr-3">
          <v-menu
            ref="startDateMenu"
            v-model="startDateMenu"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="form.eventstart"
                :label="$t('start_date')"
                :rules="[(v) => !!v || $t('field_required')]"
                readonly
                v-bind="attrs"
                v-on="on"
                outlined
                dense
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="form.eventstart"
              :locale="settings.locale"
              no-title
              scrollable
              @input="startDateMenu = false"
            ></v-date-picker>
          </v-menu>
          <v-menu
            ref="startTimeMenu"
            v-model="startTimeMenu"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="form.eventstarttime"
                :label="$t('start_time')"
                readonly
                v-bind="attrs"
                v-on="on"
                outlined
                dense
              ></v-text-field>
            </template>
            <v-time-picker
              v-model="form.eventstarttime"
              format="24hr"
              scrollable
              @input="startTimeMenu = false"
            ></v-time-picker>
          </v-menu>
        </v-col>

        <v-col md="6" sm="12" cols="12" class="pa-0 pr-3">
          <v-menu
            ref="endDateMenu"
            v-model="endDateMenu"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="form.eventend"
                :label="$t('end_date')"
                readonly
                v-bind="attrs"
                v-on="on"
                outlined
                dense
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="form.eventend"
              :locale="locale"
              no-title
              scrollable
              @input="endDateMenu = false"
            ></v-date-picker>
          </v-menu>
          <v-menu
            ref="endTimeMenu"
            v-model="endTimeMenu"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="form.eventendtime"
                :label="$t('end_time')"
                readonly
                v-bind="attrs"
                v-on="on"
                outlined
                dense
              ></v-text-field>
            </template>
            <v-time-picker
              v-model="form.eventendtime"
              format="24hr"
              scrollable
              @input="endTimeMenu = false"
            ></v-time-picker>
          </v-menu>
        </v-col>

        <v-col md="6" sm="12" cols="12" class="pa-0 pr-3">
          <v-autocomplete
            v-model="form.eventreminder"
            :items="reminderOptions"
            :label="$t('reminder_before_event')"
            outlined
            dense
          ></v-autocomplete>
        </v-col>

        <v-col md="6" sm="12" cols="12" class="pa-0 pr-3">
          <v-text-field
            v-model="form.eventlink"
            :label="$t('link')"
            type="url"
            placeholder="https://"
            outlined
            class="link-field"
            dense
          ></v-text-field>
        </v-col>

        <v-col md="12" sm="12" cols="12" class="pa-0 pr-3">
          <v-textarea
            v-model="form.eventnote"
            :label="$t('notes')"
            rows="3"
            counter="500"
            outlined
          ></v-textarea>
        </v-col>
      </v-row>
    </v-form>
    <!-- </v-card> -->
  </v-container>
</template>

<script>
export default {
  name: "EventTab",
  props: {
    settings: {
      type: Object,
    },
    getsection: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      valid: true,
      form: {
        eventtitle: "",
        eventlocation: "",
        eventstart: "",
        eventstarttime: "",
        eventend: "",
        eventendtime: "",
        eventreminder: "",
        eventlink: "",
        eventnote: "",
      },
      startDateMenu: false,
      startTimeMenu: false,
      endDateMenu: false,
      endTimeMenu: false,
      reminderOptions: [
        { text: "--", value: "" },
        { text: this.$t("when_the_event_starts"), value: "PT0M" },
        { text: `5 ${this.$t("minutes")}`, value: "-PT5M" },
        { text: `10 ${this.$t("minutes")}`, value: "-PT10M" },
        { text: `15 ${this.$t("minutes")}`, value: "-PT15M" },
        { text: `30 ${this.$t("minutes")}`, value: "-PT30M" },
        { text: `1 ${this.$t("hour")}`, value: "-PT1H" },
        { text: `2 ${this.$t("hours")}`, value: "-PT2H" },
        { text: `3 ${this.$t("hours")}`, value: "-PT3H" },
        { text: `4 ${this.$t("hours")}`, value: "-PT4H" },
        { text: `5 ${this.$t("hours")}`, value: "-PT5H" },
        { text: `6 ${this.$t("hours")}`, value: "-PT6H" },
        { text: `12 ${this.$t("hours")}`, value: "-PT12H" },
        { text: `24 ${this.$t("hours")}`, value: "-PT24H" },
        { text: `48 ${this.$t("hours")}`, value: "-PT48H" },
        { text: `1 ${this.$t("week")}`, value: "-PT168H" },
      ],
    };
  },
  computed: {
    isActive() {
      return this.getsection === "#event" ? "show active" : "";
    },
  },
  methods: {
    validate() {
      return this.$refs.eventForm.validate();
    },
  },
};
</script>

<style scoped>
.v-card {
  padding: 16px;
}
.v-container {
  direction: rtl;
  width: 100%;
}
</style>
