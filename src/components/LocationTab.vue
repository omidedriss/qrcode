<template>
  <div
    value="location"
    class="location-tab fadeIn"
    v-if="getsection === '#location'"
    id="location"
  >
    <p class="rtl-label tag">{{ $t("buttons.location") }}</p>

    <!-- OpenLayers Map (used when Google API key is invalid) -->
    <template v-if="!isGoogleApiKeyValid">
      <v-card-text>
        <small>{{ $t("googlevalid") }}</small>
        <v-row>
          <v-col md="6" cols="12" class="mb-1">
            <v-text-field
              v-model="searchAddress"
              :label="$t('search')"
              class="venomaps-set-address nopreview"
              outlined
              dense
              append-outer-icon="mdi-magnify"
              @click:append-outer="searchCoordinates"
            />
          </v-col>
          <v-col md="3" cols="12">
            <v-text-field
              v-model.number="latitude"
              :label="$t('latitude')"
              type="number"
              class="venomaps-get-lat setinput-latlon no-validate"
              step="0.001"
              outlined
              dense
            />
          </v-col>
          <v-col md="3" cols="12">
            <v-text-field
              v-model.number="longitude"
              :label="$t('longitude')"
              type="number"
              class="venomaps-get-lon setinput-latlon no-validate"
              step="0.001"
              outlined
              dense
            />
          </v-col>
        </v-row>
        <div class="form-group">
          <div
            id="wpol-admin-map"
            :data-lat="settingsLat"
            :data-lng="settingsLng"
            class="venomap"
          ></div>
          <div style="display: none">
            <div class="wpol-infomarker" id="infomarker_admin"></div>
          </div>
        </div>
      </v-card-text>
    </template>

    <!-- Google Maps (used when Google API key is valid) -->
    <template v-else>
      <v-card-text>
        <div style="min-height: 350px" class="mb-3">
          <div id="latlong">
            <v-text-field
              id="pac-input"
              v-model="searchAddress"
              :label="$t('search')"
              class="controls nopreview"
              outlined
              dense
            />
            <v-text-field
              id="latbox"
              v-model.number="latitude"
              :label="$t('latitude')"
              type="number"
              class="controls"
              step="0.1"
              outlined
              dense
            />
            <v-text-field
              id="lngbox"
              v-model.number="longitude"
              :label="$t('longitude')"
              type="number"
              class="controls"
              step="0.1"
              outlined
              dense
            />
          </div>
          <div
            id="map-canvas"
            :data-lat="settingsLat"
            :data-lng="settingsLng"
          ></div>
        </div>
      </v-card-text>
    </template>
  </div>
</template>

<script>
export default {
  name: "LocationTab",
  props: {
    getsection: {
      type: String,
      default: "",
    },
    settings: {
      type: Object,
      required: true,
      default: () => ({
        location: false,
        google_api_key: "",
        lat: 0,
        lng: 0,
      }),
    },
  },
  data() {
    return {
      searchAddress: "",
      latitude: null,
      longitude: null,
    };
  },
  computed: {
    isLocationEnabled() {
      console.log("isLocationEnabled:", this.settings.location);
      return this.settings.location === true;
    },
    isGoogleApiKeyValid() {
      const key = this.settings.google_api_key;
      console.log("isGoogleApiKeyValid:", {
        key,
        valid: key && key !== "YOUR-API-KEY" && key.length >= 10,
      });
      return key && key !== "YOUR-API-KEY" && key.length >= 10;
    },
    settingsLat() {
      console.log("settingsLat:", this.settings.lat || 0);
      return this.settings.lat || 0;
    },
    settingsLng() {
      console.log("settingsLng:", this.settings.lng || 0);
      return this.settings.lng || 0;
    },
  },
  mounted() {
    console.log("LocationTab mounted, activeSection:", this.activeSection);
    if (this.isGoogleApiKeyValid) {
      this.loadGoogleMapsScript();
    } else {
      this.loadOpenLayersScript();
    }
  },
  methods: {
    loadGoogleMapsScript() {
      if (!window.google || !window.google.maps) {
        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=${this.settings.google_api_key}&libraries=places`;
        script.async = true;
        script.defer = true;
        script.onload = () => {
          console.log("Google Maps script loaded");
          this.initializeGoogleMap();
        };
        script.onerror = () => {
          console.error("Failed to load Google Maps script");
        };
        document.head.appendChild(script);
      } else {
        this.initializeGoogleMap();
      }
    },
    loadOpenLayersScript() {
      if (!window.ol) {
        const script = document.createElement("script");
        script.src = "/ol/ol.js"; // Assumes ol.js is in public/ol/ol.js
        script.async = true;
        script.onload = () => {
          console.log("OpenLayers script loaded");
          this.initializeOpenLayersMap();
        };
        script.onerror = () => {
          console.error(
            "Failed to load OpenLayers script. Ensure ol.js is in public/ol/ol.js or use a CDN: https://cdn.jsdelivr.net/npm/ol@9.2.4/dist/ol.js"
          );
        };
        document.head.appendChild(script);
      } else {
        this.initializeOpenLayersMap();
      }
    },
    initializeGoogleMap() {
      const mapElement = document.getElementById("map-canvas");
      if (!mapElement) {
        console.error("Google Map container not found");
        return;
      }
      const map = new window.google.maps.Map(mapElement, {
        center: { lat: this.settingsLat, lng: this.settingsLng },
        zoom: 8,
      });
      console.log(map, "map");
      console.log("Google Map initialized at:", {
        lat: this.settingsLat,
        lng: this.settingsLng,
      });
    },
    initializeOpenLayersMap() {
      const mapElement = document.getElementById("wpol-admin-map");
      if (!mapElement) {
        console.error("OpenLayers map container not found");
        return;
      }
      const map = new window.ol.Map({
        target: "wpol-admin-map",
        layers: [
          new window.ol.layer.Tile({
            source: new window.ol.source.OSM(),
          }),
        ],
        view: new window.ol.View({
          center: window.ol.proj.fromLonLat([
            this.settingsLng,
            this.settingsLat,
          ]),
          zoom: 8,
        }),
      });
      console.log(map, "map");
      console.log("OpenLayers map initialized at:", {
        lat: this.settingsLat,
        lng: this.settingsLng,
      });
    },
    searchCoordinates() {
      console.log("Search button clicked, address:", this.searchAddress);
      // Implement search logic here (e.g., geocode address and update latitude/longitude)
    },
  },
};
</script>

<style scoped>
.venomap {
  height: 350px; /* Match min-height from Google Maps div */
}
</style>
