<template>
  <v-tab-item>
    <v-card flat>
      <v-card-title>{{ $t("location") }}</v-card-title>
      <v-card-text>
        <v-row v-if="!isValidGoogleApiKey">
          <v-col cols="12">
            <small>{{ $t("search_address_hint") }}</small>
          </v-col>
          <v-col cols="12" md="6" class="mb-1">
            <v-text-field
              v-model="searchAddress"
              :placeholder="$t('search')"
              outlined
              dense
              append-icon="mdi-magnify"
              @click:append="getCoordinates"
              class="venomaps-set-address"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <v-text-field
              v-model="latitude"
              :placeholder="$t('latitude')"
              type="number"
              step="0.001"
              outlined
              dense
              class="venomaps-get-lat no-validate"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <v-text-field
              v-model="longitude"
              :placeholder="$t('longitude')"
              type="number"
              step="0.001"
              outlined
              dense
              class="venomaps-get-lon no-validate"
            ></v-text-field>
          </v-col>
          <v-col cols="12">
            <div
              id="wpol-admin-map"
              :data-lat="settings.lat"
              :data-lng="settings.lng"
              class="venomap"
              style="height: 350px"
            ></div>
            <div style="display: none">
              <div class="wpol-infomarker" id="infomarker_admin"></div>
            </div>
          </v-col>
        </v-row>
        <v-row v-else>
          <!-- Mode with Google API key -->
          <v-col cols="12">
            <div style="min-height: 350px" class="mb-3">
              <v-text-field
                id="pac-input"
                v-model="searchAddress"
                :placeholder="$t('search')"
                outlined
                dense
                class="controls nopreview"
              ></v-text-field>
              <v-text-field
                id="latbox"
                v-model="latitude"
                :placeholder="$t('latitude')"
                type="number"
                step="0.1"
                outlined
                dense
                class="controls"
              ></v-text-field>
              <v-text-field
                id="lngbox"
                v-model="longitude"
                :placeholder="$t('longitude')"
                type="number"
                step="0.1"
                outlined
                dense
                class="controls"
              ></v-text-field>
              <div
                id="map-canvas"
                :data-lat="settings.lat"
                :data-lng="settings.lng"
                style="height: 350px"
              ></div>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-tab-item>
</template>

<script>
import { EventBus } from "./eventBus.js";
import ol from "@/assets/js/ol/ol.js";

export default {
  name: "LocationTab",
  props: {
    settings: {
      type: Object,
      required: true,
      default: () => ({
        location: false,
        google_api_key: "",
        lat: 51.505,
        lng: -0.09,
      }),
    },
    active: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      searchAddress: "",
      latitude: this.settings.lat || 0,
      longitude: this.settings.lng || 0,
      map: null,
      marker: null,
      isOpenLayersLoaded: false,
      isGoogleMapsLoaded: false,
    };
  },
  computed: {
    isValidGoogleApiKey() {
      const key = this.settings.google_api_key;
      return key && key !== "YOUR-API-KEY" && key.length >= 10;
    },
  },
  mounted() {
    if (this.settings.location) {
      if (!this.isValidGoogleApiKey) {
        this.loadOpenLayersScript();
      } else {
        this.loadGoogleMapsScript();
      }
    }
    if (this.active) {
      EventBus.$emit("updateTopTitle", "location");
    }
  },
  watch: {
    active(newVal) {
      if (newVal) {
        EventBus.$emit("updateTopTitle", "location");
      }
    },
  },
  methods: {
    loadOpenLayersScript() {
      if (typeof ol !== "undefined") {
        this.isOpenLayersLoaded = true;
        this.setupOpenLayersMap();
        return;
      }

      const script = document.createElement("script");
      script.src = "https://cdn.jsdelivr.net/npm/ol@7.1.0/dist/ol.js";
      script.async = true;
      script.onload = () => {
        this.isOpenLayersLoaded = true;
        this.setupOpenLayersMap();
      };
      script.onerror = () => {
        console.error("Failed to load OpenLayers script");
      };
      document.head.appendChild(script);
    },
    setupOpenLayersMap() {
      if (!this.isOpenLayersLoaded || typeof ol === "undefined") {
        console.error("OpenLayers is not loaded");
        return;
      }

      this.map = new ol.Map({
        target: "wpol-admin-map",
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM(),
          }),
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([this.settings.lng, this.settings.lat]),
          zoom: 15,
        }),
      });

      this.marker = new ol.Feature({
        geometry: new ol.geom.Point(
          ol.proj.fromLonLat([this.settings.lng, this.settings.lat])
        ),
      });
      const vectorSource = new ol.source.Vector({
        features: [this.marker],
      });
      const markerLayer = new ol.layer.Vector({
        source: vectorSource,
      });
      this.map.addLayer(markerLayer);

      const modify = new ol.interaction.Modify({ source: vectorSource });
      this.map.addInteraction(modify);

      modify.on("modifyend", (event) => {
        const coordinates = ol.proj.toLonLat(
          event.features.getArray()[0].getGeometry().getCoordinates()
        );
        this.longitude = coordinates[0].toFixed(3);
        this.latitude = coordinates[1].toFixed(3);
      });

      this.map.on("click", (event) => {
        const coords = ol.proj.toLonLat(event.coordinate);
        this.longitude = coords[0].toFixed(3);
        this.latitude = coords[1].toFixed(3);
        this.marker.getGeometry().setCoordinates(event.coordinate);
      });
    },
    loadGoogleMapsScript() {
      if (typeof google !== "undefined" && typeof google.maps !== "undefined") {
        this.isGoogleMapsLoaded = true;
        this.initGoogleMaps();
        return;
      }

      const script = document.createElement("script");
      script.src = `https://maps.googleapis.com/maps/api/js?key=${this.settings.google_api_key}&libraries=places&callback=initGoogleMapsCallback`;
      script.async = true;
      script.onerror = () => {
        console.error("Failed to load Google Maps script");
      };
      window.initGoogleMapsCallback = () => {
        this.isGoogleMapsLoaded = true;
        this.initGoogleMaps();
      };
      document.head.appendChild(script);
    },
    initGoogleMaps() {
      if (!this.isGoogleMapsLoaded || typeof google === "undefined") {
        console.error("Google Maps is not loaded");
        return;
      }

      const mapOptions = {
        center: {
          lat: parseFloat(this.settings.lat),
          lng: parseFloat(this.settings.lng),
        },
        zoom: 15,
      };
      this.map = new google.maps.Map(
        document.getElementById("map-canvas"),
        mapOptions
      );

      this.marker = new google.maps.Marker({
        position: mapOptions.center,
        map: this.map,
        draggable: true,
      });

      this.marker.addListener("dragend", () => {
        const position = this.marker.getPosition();
        this.latitude = position.lat().toFixed(1);
        this.longitude = position.lng().toFixed(1);
      });

      const input = document.getElementById("pac-input");
      const searchBox = new google.maps.places.SearchBox(input);
      this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;

        const bounds = new google.maps.LatLngBounds();
        places.forEach((place) => {
          if (!place.geometry) return;
          this.latitude = place.geometry.location.lat().toFixed(1);
          this.longitude = place.geometry.location.lng().toFixed(1);
          this.marker.setPosition(place.geometry.location);
          if (place.geometry.viewport) {
            bounds.union(place.geometry.viewport);
          } else {
            bounds.extend(place.geometry.location);
          }
        });
        this.map.fitBounds(bounds);
      });
    },
    getCoordinates() {
      console.log("Search address:", this.searchAddress);
      fetch(
        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
          this.searchAddress
        )}`
      )
        .then((response) => response.json())
        .then((data) => {
          if (data && data.length > 0) {
            this.latitude = parseFloat(data[0].lat).toFixed(3);
            this.longitude = parseFloat(data[0].lon).toFixed(3);
            if (this.map && this.marker) {
              this.marker
                .getGeometry()
                .setCoordinates(
                  ol.proj.fromLonLat([this.longitude, this.latitude])
                );
              this.map
                .getView()
                .setCenter(ol.proj.fromLonLat([this.longitude, this.latitude]));
            }
          }
        })
        .catch((error) => {
          console.error("Error fetching coordinates:", error);
        });
    },
  },
};
</script>

<style scoped>
.venomap {
  height: 350px;
  width: 100%;
}
.controls {
  margin-bottom: 10px;
}
</style>
