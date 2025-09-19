<template>
  <div class="static-details">
    <div class="menu-wrapper-cont">
      <div class="menu-wrapper">
        <SideMenu
          v-model="activeMenu"
          :settings="currentSettings"
          @go-back="$emit('back-to-static')"
        />
      </div>
    </div>
    <div class="show-option">
      <component
        :is="getComponentName()"
        :settings="currentSettings"
        :selected-button="selectedButton"
        v-bind="getComponentProps()"
        @update:pattern="updateProp('DesignPanel', 'pattern', $event)"
        @update:markerOut="updateProp('DesignPanel', 'markerOut', $event)"
        @update:markerIn="updateProp('DesignPanel', 'markerIn', $event)"
        @update:markersColor="updateProp('DesignPanel', 'markersColor', $event)"
        @update:markerOutColor="
          updateProp('DesignPanel', 'markerOutColor', $event)
        "
        @update:markerInColor="
          updateProp('DesignPanel', 'markerInColor', $event)
        "
        @update:differentMarkersColor="
          updateProp('DesignPanel', 'differentMarkersColor', $event)
        "
        @update:markerTopRightOutline="
          updateProp('DesignPanel', 'markerTopRightOutline', $event)
        "
        @update:markerTopRightCenter="
          updateProp('DesignPanel', 'markerTopRightCenter', $event)
        "
        @update:markerBottomLeftOutline="
          updateProp('DesignPanel', 'markerBottomLeftOutline', $event)
        "
        @update:markerBottomLeftCenter="
          updateProp('DesignPanel', 'markerBottomLeftCenter', $event)
        "
        @update:backcolor="updateProp('ColorsPanel', 'backcolor', $event)"
        @update:frontcolor="updateProp('ColorsPanel', 'frontcolor', $event)"
        @update:gradientColor="
          updateProp('ColorsPanel', 'gradientColor', $event)
        "
        @update:transparent="updateProp('ColorsPanel', 'transparent', $event)"
        @update:transparentCode="
          updateProp('ColorsPanel', 'transparentCode', $event)
        "
        @update:negativeQr="updateProp('ColorsPanel', 'negativeQr', $event)"
        @update:gradient="updateProp('ColorsPanel', 'gradient', $event)"
        @update:radial="updateProp('ColorsPanel', 'radial', $event)"
        @update:bgImage="updateProp('ColorsPanel', 'bgImage', $event)"
        @update:imageZoom="updateProp('ColorsPanel', 'imageZoom', $event)"
        @update:optionLogo="updateProp('LogoPanel', 'optionLogo', $event)"
        @update:logoFile="updateProp('LogoPanel', 'logoFile', $event)"
        @update:logoError="updateProp('LogoPanel', 'logoError', $event)"
        @update:noLogoBg="updateProp('LogoPanel', 'noLogoBg', $event)"
        @update:logoSize="updateProp('LogoPanel', 'logoSize', $event)"
        @update:customWatermark="
          updateProp('LogoPanel', 'customWatermark', $event)
        "
        @update:watermarks="updateProp('LogoPanel', 'watermarks', $event)"
        @update:uploader="updateProp('LogoPanel', 'uploader', $event)"
        @update:outerFrame="updateProp('FramePanel', 'outerFrame', $event)"
        @update:frameLabel="updateProp('FramePanel', 'frameLabel', $event)"
        @update:labelFont="updateProp('FramePanel', 'labelFont', $event)"
        @update:labelTextSize="
          updateProp('FramePanel', 'labelTextSize', $event)
        "
        @update:customFrameColor="
          updateProp('FramePanel', 'customFrameColor', $event)
        "
        @update:frameColor="updateProp('FramePanel', 'frameColor', $event)"
        @update:fonts="updateProp('FramePanel', 'fonts', $event)"
        @update:size="updateProp('OptionsPanel', 'size', $event)"
        @update:level="updateProp('OptionsPanel', 'level', $event)"
      />
    </div>
    <div class="show-option-cont">
      <div class="show-qr">
        <img :src="qrCodeImage" alt="Qr Code" class="qrCodeImage" />
        <v-btn class="save-button" @click="showSaveOptions = !showSaveOptions">
          <v-icon>mdi-download</v-icon>
          {{ $t("save") }}
        </v-btn>
        <div v-if="showSaveOptions" class="save-options">
          <v-btn
            icon
            @click="
              saveAs('png');
              handleMobileClick('png');
            "
            @mouseenter="hoveredButton = 'png'"
            @mouseleave="hoveredButton = null"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="3em"
              height="3em"
              :fill="hoveredButton === 'png' ? settings.butttonColor : 'white'"
              viewBox="0 0 48 48"
            >
              <path
                d="M28.9,18.4l-3.4,3.4V10.5C25.5,9.7,24.8,9,24,9s-1.5,0.7-1.5,1.5v11.4l-3.4-3.4c-0.6-0.6-1.5-0.6-2.1,0 c-0.6,0.6-0.6,1.5,0,2.1l6,6c0.6,0.6,1.5,0.6,2.1,0c0,0,0,0,0,0l6-6c0.6-0.6,0.6-1.5,0-2.1C30.5,17.9,29.5,17.9,28.9,18.4z"
              ></path>
              <path
                d="M42,30V13.5L28.5,0H12C8.7,0,6,2.7,6,6v24c-1.7,0.1-3,1.4-3,3.1v5.8c0,1.7,1.3,3,3,3.1v0c0,3.3,2.7,6,6,6h24 c3.3,0,6-2.7,6-6v0c1.7-0.1,3-1.4,3-3.1v-5.8C45,31.4,43.7,30.1,42,30z M36,45H12c-1.7,0-3-1.3-3-3h30C39,43.7,37.7,45,36,45z M11.5,39.3v-6.6c0-0.4,0.1-0.7,0.3-0.8s0.5-0.2,0.8-0.2h2.2c0.7,0,1.2,0.1,1.5,0.2c0.4,0.1,0.7,0.3,0.9,0.5s0.4,0.5,0.6,0.8 s0.2,0.7,0.2,1.1c0,0.9-0.3,1.5-0.8,2s-1.3,0.7-2.4,0.7h-1.6v2.4c0,0.3-0.1,0.6-0.2,0.8s-0.4,0.3-0.6,0.3c-0.3,0-0.5-0.1-0.6-0.3 S11.5,39.6,11.5,39.3z M19.5,39.3v-6.6c0-0.3,0-0.5,0.1-0.7c0.1-0.2,0.2-0.3,0.4-0.4s0.4-0.2,0.6-0.2c0.2,0,0.3,0,0.4,0.1 s0.2,0.1,0.3,0.2s0.2,0.2,0.3,0.3s0.2,0.3,0.3,0.4l3.3,5.1v-5.1c0-0.3,0.1-0.6,0.2-0.7s0.3-0.2,0.6-0.2c0.3,0,0.4,0.1,0.6,0.2 s0.2,0.4,0.2,0.7v6.8c0,0.8-0.3,1.1-0.9,1.1c-0.2,0-0.3,0-0.4-0.1s-0.2-0.1-0.4-0.2s-0.2-0.2-0.3-0.3s-0.2-0.3-0.3-0.4l-3.3-5v5 c0,0.3-0.1,0.6-0.2,0.7s-0.3,0.3-0.6,0.3c-0.2,0-0.4-0.1-0.6-0.3S19.5,39.7,19.5,39.3z M30.7,38.2c0.5,0.5,1.1,0.8,1.9,0.8 c0.4,0,0.8-0.1,1.1-0.2s0.7-0.3,1.1-0.5V37h-1.3c-0.3,0-0.6,0-0.7-0.1s-0.2-0.3-0.2-0.5c0-0.2,0.1-0.4,0.2-0.5s0.3-0.2,0.6-0.2h2 c0.2,0,0.4,0,0.6,0.1s0.3,0.1,0.4,0.3s0.2,0.4,0.2,0.7v1.6c0,0.2,0,0.4-0.1,0.5s-0.1,0.2-0.2,0.4s-0.3,0.2-0.4,0.3 c-0.5,0.3-1,0.5-1.5,0.6s-1,0.2-1.6,0.2c-0.7,0-1.3-0.1-1.8-0.3s-1-0.5-1.4-0.9s-0.7-0.9-0.9-1.4s-0.3-1.2-0.3-1.8 c0-0.7,0.1-1.3,0.3-1.8s0.5-1,0.9-1.4s0.9-0.7,1.4-0.9s1.2-0.3,1.9-0.3c0.6,0,1.1,0.1,1.5,0.2s0.8,0.3,1.1,0.6s0.5,0.5,0.6,0.7 s0.2,0.5,0.2,0.7c0,0.2-0.1,0.4-0.2,0.6s-0.4,0.2-0.6,0.2c-0.1,0-0.2,0-0.4-0.1s-0.2-0.1-0.3-0.2c-0.2-0.3-0.4-0.6-0.5-0.8 s-0.3-0.3-0.6-0.4s-0.6-0.2-1-0.2c-0.4,0-0.8,0.1-1.1,0.2s-0.6,0.3-0.8,0.6s-0.4,0.6-0.5,1S30,35.4,30,35.9 C30,36.9,30.3,37.6,30.7,38.2z M39,30H9V6c0-1.7,1.3-3,3-3h16.5v6c0,2.5,2,4.5,4.5,4.5h6V30z"
              ></path>
              <path
                d="M15.5,35.4c0.3-0.1,0.5-0.2,0.6-0.4s0.2-0.5,0.2-0.8c0-0.4-0.1-0.7-0.3-0.9c-0.3-0.3-0.8-0.4-1.5-0.4h-1.2v2.6h1.2 C14.8,35.5,15.2,35.5,15.5,35.4z"
              ></path>
            </svg>
          </v-btn>
          <v-btn
            icon
            @click="
              saveAs('svg');
              handleMobileClick('svg');
            "
            @mouseenter="hoveredButton = 'svg'"
            @mouseleave="hoveredButton = null"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="3em"
              height="3em"
              :fill="hoveredButton === 'svg' ? settings.butttonColor : 'white'"
              viewBox="0 0 48 48"
            >
              <path
                d="M28.9,18.4l-3.4,3.4V10.5C25.5,9.7,24.8,9,24,9s-1.5,0.7-1.5,1.5v11.4l-3.4-3.4c-0.6-0.6-1.5-0.6-2.1,0 c-0.6,0.6-0.6,1.5,0,2.1l6,6c0.6,0.6,1.5,0.6,2.1,0c0,0,0,0,0,0l6-6c0.6-0.6,0.6-1.5,0-2.1C30.5,17.9,29.5,17.9,28.9,18.4z"
              ></path>
              <path
                d="M42,30V13.5L28.5,0H12C8.7,0,6,2.7,6,6v24c-1.7,0.1-3,1.4-3,3.1v5.8c0,1.7,1.3,3,3,3.1v0c0,3.3,2.7,6,6,6h24 c3.3,0,6-2.7,6-6v0c1.7-0.1,3-1.4,3-3.1v-5.8C45,31.4,43.7,30.1,42,30z M36,45H12c-1.7,0-3-1.3-3-3h30C39,43.7,37.7,45,36,45z M16.6,37.2c-0.2-0.2-0.4-0.3-0.7-0.4c-0.3-0.1-0.6-0.2-1.1-0.3c-0.6-0.1-1.1-0.3-1.6-0.5c-0.4-0.2-0.8-0.5-1-0.8 c-0.2-0.3-0.4-0.8-0.4-1.3c0-0.5,0.1-0.9,0.4-1.3c0.3-0.4,0.6-0.7,1.1-0.9c0.5-0.2,1.1-0.3,1.7-0.3c0.5,0,1,0.1,1.4,0.2 c0.4,0.1,0.7,0.3,1,0.5c0.3,0.2,0.4,0.4,0.6,0.7c0.1,0.2,0.2,0.5,0.2,0.7c0,0.2-0.1,0.4-0.2,0.6s-0.3,0.3-0.6,0.3 c-0.2,0-0.4-0.1-0.5-0.2c-0.1-0.1-0.2-0.3-0.3-0.5c-0.2-0.3-0.3-0.6-0.6-0.8c-0.2-0.2-0.6-0.3-1.1-0.3c-0.5,0-0.8,0.1-1.1,0.3 s-0.4,0.4-0.4,0.7c0,0.2,0,0.3,0.1,0.4c0.1,0.1,0.2,0.2,0.4,0.3c0.2,0.1,0.3,0.2,0.5,0.2c0.2,0.1,0.4,0.1,0.8,0.2 c0.5,0.1,0.9,0.2,1.3,0.4c0.4,0.1,0.7,0.3,1,0.5c0.3,0.2,0.5,0.4,0.6,0.7s0.2,0.7,0.2,1.1c0,0.5-0.1,1-0.4,1.4 c-0.3,0.4-0.7,0.7-1.2,1c-0.5,0.2-1.1,0.4-1.8,0.4c-0.9,0-1.6-0.2-2.1-0.5c-0.4-0.2-0.7-0.5-1-0.9c-0.2-0.4-0.4-0.8-0.4-1.1 c0-0.2,0.1-0.4,0.2-0.5s0.3-0.2,0.6-0.2c0.2,0,0.3,0.1,0.5,0.2c0.1,0.1,0.2,0.3,0.3,0.5c0.1,0.3,0.2,0.5,0.4,0.7s0.3,0.3,0.5,0.5 c0.2,0.1,0.5,0.2,0.9,0.2c0.5,0,0.9-0.1,1.3-0.4c0.3-0.2,0.5-0.5,0.5-0.9C16.9,37.7,16.8,37.4,16.6,37.2z M19.5,32.4 c0-0.2,0.1-0.4,0.2-0.5s0.4-0.2,0.6-0.2c0.3,0,0.5,0.1,0.6,0.3c0.1,0.2,0.2,0.5,0.4,0.9l2,5.8l2-5.8c0.1-0.3,0.2-0.5,0.2-0.6 c0.1-0.1,0.1-0.2,0.3-0.3c0.1-0.1,0.3-0.1,0.5-0.1c0.2,0,0.3,0,0.4,0.1c0.1,0.1,0.2,0.2,0.3,0.3s0.1,0.2,0.1,0.4c0,0.1,0,0.2,0,0.3 S27,32.8,27,32.9c0,0.1-0.1,0.2-0.1,0.3l-2.1,5.6c-0.1,0.2-0.2,0.4-0.2,0.6c-0.1,0.2-0.2,0.4-0.3,0.5s-0.2,0.3-0.4,0.4 s-0.4,0.1-0.6,0.1c-0.2,0-0.4,0-0.6-0.1c-0.2-0.1-0.3-0.2-0.4-0.4c-0.1-0.2-0.2-0.3-0.3-0.5c-0.1-0.2-0.1-0.4-0.2-0.6l-2.1-5.6 c0-0.1-0.1-0.2-0.1-0.3c0-0.1-0.1-0.2-0.1-0.3C19.5,32.5,19.5,32.4,19.5,32.4z M30.4,38.3c0.5,0.5,1.1,0.8,1.9,0.8 c0.4,0,0.8-0.1,1.1-0.2c0.4-0.1,0.7-0.3,1.1-0.5v-1.4h-1.4c-0.3,0-0.6,0-0.7-0.1c-0.2-0.1-0.3-0.3-0.3-0.5c0-0.2,0.1-0.4,0.2-0.5 c0.1-0.1,0.3-0.2,0.6-0.2h2c0.2,0,0.5,0,0.6,0.1c0.2,0,0.3,0.1,0.4,0.3c0.1,0.1,0.2,0.4,0.2,0.7v1.7c0,0.2,0,0.4-0.1,0.5 c0,0.1-0.1,0.2-0.2,0.4s-0.3,0.2-0.4,0.3c-0.5,0.3-1,0.5-1.5,0.6s-1,0.2-1.6,0.2c-0.7,0-1.3-0.1-1.8-0.3c-0.5-0.2-1-0.5-1.4-0.9 c-0.4-0.4-0.7-0.9-0.9-1.4c-0.2-0.6-0.3-1.2-0.3-1.9c0-0.7,0.1-1.3,0.3-1.8c0.2-0.6,0.5-1,0.9-1.4s0.9-0.7,1.4-0.9 c0.6-0.2,1.2-0.3,1.9-0.3c0.6,0,1.1,0.1,1.5,0.2s0.8,0.4,1.1,0.6c0.3,0.2,0.5,0.5,0.6,0.7c0.1,0.3,0.2,0.5,0.2,0.7 c0,0.2-0.1,0.4-0.2,0.6s-0.4,0.2-0.6,0.2c-0.1,0-0.2,0-0.4-0.1c-0.1-0.1-0.2-0.1-0.3-0.2c-0.2-0.3-0.4-0.6-0.5-0.8 c-0.1-0.2-0.4-0.3-0.6-0.4c-0.3-0.1-0.6-0.2-1-0.2c-0.4,0-0.8,0.1-1.1,0.2s-0.6,0.3-0.8,0.6s-0.4,0.6-0.5,1 c-0.1,0.4-0.2,0.8-0.2,1.3C29.7,37,29.9,37.8,30.4,38.3z M39,30H9V6c0-1.7,1.3-3,3-3h16.5v6 c0,2.5,2,4.5,4.5,4.5h6V30z"
              ></path>
            </svg>
          </v-btn>
          <v-btn
            icon
            @click="
              saveAs('pdf');
              handleMobileClick('pdf');
            "
            @mouseenter="hoveredButton = 'pdf'"
            @mouseleave="hoveredButton = null"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="3em"
              height="3em"
              :fill="hoveredButton === 'pdf' ? settings.butttonColor : 'white'"
              viewBox="0 0 48 48"
            >
              <path
                d="M28.9,18.4l-3.4,3.4V10.5C25.5,9.7,24.8,9,24,9s-1.5,0.7-1.5,1.5v11.4l-3.4-3.4c-0.6-0.6-1.5-0.6-2.1,0 c-0.6,0.6-0.6,1.5,0,2.1l6,6c0.6,0.6,1.5,0.6,2.1,0l6-6c0.6-0.6,0.6-1.5,0-2.1C30.5,17.9,29.5,17.9,28.9,18.4z"
              ></path>
              <path
                d="M24.6,39c0.2,0,0.3-0.1,0.5-0.1c0.2-0.1,0.3-0.2,0.5-0.3c0.6-0.5,0.9-1.4,0.9-2.6c0-0.9-0.1-1.5-0.4-2 c-0.3-0.4-0.6-0.7-1-0.8C24.8,33,24.3,33,23.8,33h-1.2v6h1.4C24.2,39,24.5,39,24.6,39z"
              ></path>
              <path
                d="M42,30V13.5L28.5,0H12C8.7,0,6,2.7,6,6v24c-1.7,0.1-3,1.4-3,3.1v5.8c0,1.7,1.3,3,3,3.1c0,3.3,2.7,6,6,6h24c3.3,0,6-2.7,6-6 c1.7-0.1,3-1.4,3-3.1v-5.8C45,31.4,43.7,30.1,42,30z M36,45H12c-1.7,0-3-1.3-3-3h30C39,43.7,37.7,45,36,45z M12.5,39.5v-6.8 c0-0.4,0.1-0.7,0.3-0.8c0.2-0.2,0.5-0.3,0.9-0.3H16c0.7,0,1.2,0.1,1.6,0.2c0.4,0.1,0.7,0.3,0.9,0.5c0.3,0.2,0.5,0.5,0.6,0.9 c0.1,0.3,0.2,0.7,0.2,1.1c0,0.9-0.3,1.6-0.8,2C17.9,36.7,17.1,37,16,37h-1.6v2.5c0,0.4-0.1,0.6-0.3,0.8c-0.2,0.2-0.4,0.3-0.6,0.3 c-0.3,0-0.5-0.1-0.7-0.3C12.6,40.1,12.5,39.8,12.5,39.5z M20.8,39.1v-6.5c0-0.4,0.1-0.7,0.3-0.8c0.2-0.2,0.5-0.3,0.8-0.3h2.3 c0.6,0,1.1,0.1,1.6,0.2c0.4,0.1,0.8,0.3,1.2,0.6c0.9,0.8,1.4,2,1.4,3.6c0,0.5,0,1-0.1,1.5c-0.1,0.4-0.2,0.8-0.4,1.2 c-0.2,0.4-0.4,0.7-0.7,0.9c-0.2,0.2-0.5,0.4-0.8,0.5c-0.3,0.1-0.6,0.2-0.9,0.3s-0.7,0.1-1.1,0.1h-2.3c-0.3,0-0.6-0.1-0.7-0.1 c-0.2-0.1-0.3-0.2-0.3-0.4C20.8,39.7,20.8,39.4,20.8,39.1z M34.6,35.2c0.3,0,0.5,0.1,0.6,0.2c0.1,0.1,0.2,0.3,0.2,0.5 c0,0.2-0.1,0.4-0.2,0.5c-0.1,0.1-0.3,0.2-0.6,0.2h-2.9v2.9c0,0.4-0.1,0.6-0.2,0.8s-0.4,0.3-0.6,0.3s-0.5-0.1-0.6-0.3 c-0.2-0.2-0.2-0.5-0.2-0.8v-6.8c0-0.3,0-0.5,0.1-0.6s0.2-0.3,0.4-0.4s0.4-0.1,0.6-0.1h4.2c0.3,0,0.5,0.1,0.6,0.2 C36,31.9,36,32,36,32.2c0,0.2-0.1,0.4-0.2,0.5c-0.1,0.1-0.4,0.2-0.6,0.2h-3.5v2.3H34.6z M39,30H9V6c0-1.7,1.3-3,3-3h16.5v6 c0,2.5,2,4.5,4.5,4.5h6V30z"
              ></path>
              <path
                d="M16.6,35.5c0.3-0.1,0.5-0.2,0.6-0.4c0.2-0.2,0.2-0.5,0.2-0.8c0-0.4-0.1-0.7-0.3-1c-0.3-0.3-0.8-0.4-1.6-0.4h-1.2v2.7h1.2 C16,35.6,16.3,35.6,16.6,35.5z"
              ></path>
            </svg>
          </v-btn>
          <v-btn
            icon
            @click="
              printQr();
              handleMobileClick('print');
            "
            @mouseenter="hoveredButton = 'print'"
            @mouseleave="hoveredButton = null"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="3em"
              height="3em"
              :fill="
                hoveredButton === 'print' ? settings.butttonColor : 'white'
              "
              viewBox="0 0 16 16"
            >
              <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
              <path
                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"
              ></path>
            </svg>
          </v-btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { markers, markersIn } from "./markers.js";
import SideMenu from "./SideMenu.vue";
import LinkTab from "./LinkTab.vue";
console.log("LinkTab component:", LinkTab);
import TextTab from "./TextTab.vue";
import EmailTab from "./EmailTab.vue";
import LocationTab from "./LocationTab.vue";
import TelTab from "./TelTab.vue";
import SmsTab from "./SmsTab.vue";
import WhatsAppTab from "./WhatsAppTab.vue";
import instagramTab from "./instagramTab.vue";
import EthereumTab from "./EthereumTab.vue";
import ZoomTab from "./ZoomTab.vue";
import WifiTab from "./WifiTab.vue";
import VCardTab from "./VCardTab.vue";
import EventTab from "./EventTab.vue";
import PaypalTab from "./PaypalTab.vue";
import BitcoinTab from "./BitcoinTab.vue";
import DesignPanel from "./DesignPanel.vue";
import ColorsPanel from "./ColorsPanel.vue";
import LogoPanel from "./LogoPanel.vue";
import FramePanel from "./FramePanel.vue";
import OptionsPanel from "./OptionsPanel.vue";
import { EventBus } from "./eventBus";
import placeholder from "../assets/images/placeholder.svg";
import frames from "./frames.js";

export default {
  name: "StaticDetails",
  components: {
    SideMenu,
    LinkTab,
    TextTab,
    EmailTab,
    LocationTab,
    TelTab,
    SmsTab,
    WhatsAppTab,
    instagramTab,
    EthereumTab,
    ZoomTab,
    WifiTab,
    VCardTab,
    EventTab,
    PaypalTab,
    BitcoinTab,
    DesignPanel,
    ColorsPanel,
    LogoPanel,
    FramePanel,
    OptionsPanel,
  },
  props: ["settings", "selectedButton", "nameQr"],
  data() {
    return {
      activeMenu: 0,
      showSaveOptions: false,
      qrCodeImage: placeholder,
      fonts: ["Roboto", "Arial", "Times New Roman"],
      currentSettings: {},
      // Single hover state for all buttons
      hoveredButton: null,
    };
  },
  created() {
    this.currentSettings = JSON.parse(JSON.stringify(this.settings));
  },
  mounted() {
    console.log("settings:", this.settings);
    console.log("currentSettings:", this.currentSettings);
    console.log("nameQr:", this.nameQr);
    EventBus.$on("update-active-menu", this.handleActiveMenuUpdate);
    this.getComponentName();
  },
  beforeDestroy() {
    EventBus.$off("update-active-menu", this.handleActiveMenuUpdate);
  },
  watch: {
    activeMenu(newVal) {
      console.log(
        "activeMenu changed:",
        newVal,
        "Component:",
        this.getComponentName()
      );
    },
    settings: {
      handler(newSettings) {
        this.currentSettings = JSON.parse(JSON.stringify(newSettings));
        console.log(this.currentSettings, "settings");
      },
      deep: true,
    },
  },
  methods: {
    getComponentProps() {
      const menuItem = this.currentSettings.menuItems[this.activeMenu];
      if (!menuItem) return {};
      const patterns = Object.keys(markersIn).reduce((acc, key) => {
        if (markersIn[key].preview) {
          acc[key] = markersIn[key];
        }
        return acc;
      }, {});

      const markersInFiltered = Object.keys(markersIn).reduce((acc, key) => {
        if (markersIn[key].marker === true) {
          acc[key] = markersIn[key];
        }
        return acc;
      }, {});

      const watermarks = [
        {
          src: require("../assets/images/watermarks/01-link.png"),
          value: "link",
          alt: "Link Watermark",
        },
        {
          src: require("../assets/images/watermarks/01-link-b.png"),
          value: "link-b",
          alt: "Link B Watermark",
        },
        {
          src: require("../assets/images/watermarks/02-email.png"),
          value: "email",
          alt: "Email Watermark",
        },
        {
          src: require("../assets/images/watermarks/02-email-b.png"),
          value: "email-b",
          alt: "Email B Watermark",
        },
        {
          src: require("../assets/images/watermarks/03-location.png"),
          value: "location",
          alt: "Location Watermark",
        },
        {
          src: require("../assets/images/watermarks/03-location-b.png"),
          value: "location-b",
          alt: "Location B Watermark",
        },
        {
          src: require("../assets/images/watermarks/04-whatsapp.png"),
          value: "whatsapp",
          alt: "WhatsApp Watermark",
        },

        {
          src: require("../assets/images/watermarks/06-zoom.png"),
          value: "zoom",
          alt: "Zoom Watermark",
        },
        {
          src: require("../assets/images/watermarks/06-zoom-b.png"),
          value: "zoom-b",
          alt: "Zoom B Watermark",
        },
        {
          src: require("../assets/images/watermarks/07-wifi.png"),
          value: "wifi",
          alt: "WiFi Watermark",
        },
        {
          src: require("../assets/images/watermarks/07-wifi-b.png"),
          value: "wifi-b",
          alt: "WiFi B Watermark",
        },
        {
          src: require("../assets/images/watermarks/08-vcard.png"),
          value: "vcard",
          alt: "vCard Watermark",
        },
        {
          src: require("../assets/images/watermarks/08-vcard-b.png"),
          value: "vcard-b",
          alt: "vCard B Watermark",
        },
        {
          src: require("../assets/images/watermarks/09-calendar.png"),
          value: "calendar",
          alt: "Calendar Watermark",
        },
        {
          src: require("../assets/images/watermarks/09-calendar-b.png"),
          value: "calendar-b",
          alt: "Calendar B Watermark",
        },
        {
          src: require("../assets/images/watermarks/10-paypal.png"),
          value: "paypal",
          alt: "Paypal Watermark",
        },
        {
          src: require("../assets/images/watermarks/11-btc.png"),
          value: "btc",
          alt: "Bitcoin Watermark",
        },
        {
          src: require("../assets/images/watermarks/11-btc-b.png"),
          value: "btc-b",
          alt: "Bitcoin B Watermark",
        },
      ];

      const fonts = [
        "AbrilFatface",
        "CormorantGaramond",
        "FredokaOne",
        "Galindo",
        "OleoScript",
        "Parastoo",
        "PlayfairDisplay",
        "Shabnam",
        "Shrikhand",
        "Tanha",
        "Vazir",
        "Yekan",
        "ZCOOLKuaiLe",
      ];

      const baseProps = {
        settings: this.currentSettings,
        selectedButton: this.selectedButton,
      };

      const componentProps = {
        ColorsPanel: {
          backcolor: this.currentSettings.ColorsPanel.backcolor || "#FFFFFF",
          frontcolor: this.currentSettings.ColorsPanel.frontcolor || "#000000",
          gradientColor:
            this.currentSettings.ColorsPanel.gradientColor || "#8900D5",
          transparent: this.currentSettings.ColorsPanel.transparent || false,
          transparentCode:
            this.currentSettings.ColorsPanel.transparentCode || false,
          negativeQr: this.currentSettings.ColorsPanel.negativeQr || false,
          gradient: this.currentSettings.ColorsPanel.gradient || false,
          radial: this.currentSettings.ColorsPanel.radial || false,
          bgImage: this.currentSettings.ColorsPanel.bgImage || "",
          imageZoom: this.currentSettings.ColorsPanel.imageZoom || 0,
        },
        DesignPanel: {
          pattern: this.currentSettings.DesignPanel.pattern || "default",
          markerOut: this.currentSettings.DesignPanel.markerOut || "default",
          markerIn: this.currentSettings.DesignPanel.markerIn || "default",
          markersColor: this.currentSettings.DesignPanel.markersColor || false,
          markerOutColor:
            this.currentSettings.DesignPanel.markerOutColor || "#000000",
          markerInColor:
            this.currentSettings.DesignPanel.markerInColor instanceof Object
              ? "#000000"
              : this.currentSettings.DesignPanel.markerInColor || "#000000",
          differentMarkersColor:
            this.currentSettings.DesignPanel.differentMarkersColor || false,
          markerTopRightOutline:
            this.currentSettings.DesignPanel.markerTopRightOutline || "#000000",
          markerTopRightCenter:
            this.currentSettings.DesignPanel.markerTopRightCenter || "#000000",
          markerBottomLeftOutline:
            this.currentSettings.DesignPanel.markerBottomLeftOutline ||
            "#000000",
          markerBottomLeftCenter:
            this.currentSettings.DesignPanel.markerBottomLeftCenter ||
            "#000000",
          patterns,
          markers,
          markersIn: markersInFiltered,
        },
        LogoPanel: {
          optionLogo: this.currentSettings.LogoPanel.optionLogo || "none",
          logoFile: this.currentSettings.LogoPanel.logoFile || null,
          logoError: this.currentSettings.LogoPanel.logoError || null,
          noLogoBg: this.currentSettings.LogoPanel.noLogoBg || false,
          logoSize: this.currentSettings.LogoPanel.logoSize || 100,
          customWatermark:
            this.currentSettings.LogoPanel.customWatermark || null,
          watermarks,
          uploader: this.currentSettings.LogoPanel.uploader || true,
        },
        FramePanel: {
          outerFrame: this.currentSettings.FramePanel.outerFrame || "none",
          frameLabel:
            this.currentSettings.FramePanel.frameLabel || this.$t("scan_me"),
          labelFont: this.currentSettings.FramePanel.labelFont || "",
          labelTextSize: this.currentSettings.FramePanel.labelTextSize || 100,
          customFrameColor:
            this.currentSettings.FramePanel.customFrameColor || false,
          frameColor: this.currentSettings.FramePanel.frameColor || "#000000",
          frames,
          fonts,
        },
        OptionsPanel: {
          size: this.currentSettings.OptionsPanel.size || 8,
          level: this.currentSettings.OptionsPanel.level || "L",
          sizeOptions: [8, 12, 16, 20, 24, 28, 32].map((i) => ({
            value: i,
            text: i * 25,
          })),
          levelOptions: [
            { value: "L", text: this.$t("precision_l") },
            { value: "M", text: this.$t("precision_m") },
            { value: "Q", text: this.$t("precision_q") },
            { value: "H", text: this.$t("precision_h") },
          ],
        },

        LinkTab: {
          getsection: "#link",
        },
        TelTab: {
          getsection: "#tel",
        },
        SmsTab: {
          getsection: "#sms",
        },
        EmailTab: {
          getsection: "#email",
        },
        TextTab: {
          getsection: "#text",
        },
        LocationTab: {
          getsection: "#location",
        },
        WhatsAppTab: {
          getsection: "#whatsapp",
        },
        instagramTab: {
          getsection: "#instagram",
        },
        EthereumTab: {
          getsection: "#ethereum",
        },
        ZoomTab: {
          getsection: "#zoom",
        },
        WifiTab: {
          getsection: "#wifi",
        },
        VCardTab: {
          getsection: "#vcard",
        },
        EventTab: {
          getsection: "#event",
        },
        PaypalTab: {
          getsection: "#paypal",
        },
        BitcoinTab: {
          getsection: "#bitcoin",
        },
      };

      const componentName = this.getComponentName();
      return { ...baseProps, ...(componentProps[componentName] || {}) };
    },
    updateProp(panel, prop, value) {
      console.log(`Updating ${panel}.${prop}:`, value);
      this.$set(this.currentSettings[panel], prop, value);
      this.updateQrCode();
    },
    handleActiveMenuUpdate(newActiveMenu) {
      console.log("Received activeMenu update:", newActiveMenu);
      this.activeMenu = Number(newActiveMenu) || 0;
    },
    getComponentName() {
      const menuItem = this.currentSettings.menuItems[this.activeMenu];
      console.log("menuItem:", menuItem);
      console.log("nameQr:", this.nameQr);
      if (!menuItem) {
        console.warn("No menuItem found for activeMenu:", this.activeMenu);
        return null;
      }
      if (menuItem.value === "info") {
        console.log("Returning nameQr:", this.nameQr);
        return this.nameQr;
      } else {
        console.log("Returning menuItem.value:", menuItem.value);
        return menuItem.value;
      }
    },
    saveAs(format) {
      console.log("Saving as:", format);
      // this.showSaveOptions = false;
    },
    printQr() {
      window.print();
      // this.showSaveOptions = false;
    },
    updateQrCode() {
      console.log(
        "QR code updated with currentSettings:",
        this.currentSettings
      );
    },
    handleMobileClick(buttonType) {
      if (this.hoveredButton === buttonType) {
        this.hoveredButton = null;
      } else {
        this.hoveredButton = buttonType;
      }
    },
  },
};
</script>

<style scoped>
.show-option {
  overflow-y: auto;

  position: relative;
  top: 20px;
  align-self: flex-start;
  background: rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(5px);
  border-radius: 10px;
  width: 63%;
  min-width: 500px;
  flex-grow: 1;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 10px;
  height: 560px;
  min-height: 500px;
  margin-right: 10px;
  display: flex;
  justify-content: start;
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.show-option::-webkit-scrollbar {
  display: none;
}

.show-qr {
  position: -webkit-sticky;
  position: sticky;
  top: 0;
  /* position: relative; */
  align-self: flex-start;
  justify-content: start !important;
  background: v-bind("settings.menuBgColor");
  width: 310px;
  min-width: 200px;
  height: max-content;
  min-height: auto;
  border-radius: 10px;
  flex-grow: 1;
  text-align: center;
  display: flex;
  padding: 0px;
  margin: 0px;
  margin-top: 40px;

  flex-direction: column;
  align-items: center;
  height: v-bind("showSaveOptions ? 460 : 400");
  padding: 15px;
  box-sizing: border-box;
  justify-content: start;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
  transition: height 0.3s ease;
}
.show-qr img {
  width: 275px;
  height: 275px;
  border-radius: 10px;
  border: #666 solid 2px;
  margin: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}
.save-button {
  margin-top: 4px;
  text-align: center;
  color: white;
  background: v-bind("settings.butttonColor") !important;
  border-radius: 15px !important;
  padding-left: 100px !important;
  padding-right: 100px !important;
}
.save-button:hover,
.save-button:active {
  scale: 1.05 !important;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4) !important;
}
.static-details {
  display: flex;
  min-height: 500px;
  height: 600px;
  flex-direction: row;
  gap: 20px;
  width: 98%;
  justify-content: center;
  padding-left: 20px;
  /* height: 100vh; */
  background: v-bind("settings.backgroundColor");
  align-items: flex-start;
  padding-bottom: 20px;
}

/* Force menu to stay on the left side in all languages */
.static-details .menu-wrapper {
  order: -1; /* Always keep menu first (left side) */
}

.menu-wrapper {
  margin-top: 20px;
  width: 120px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  border-radius: 0 15px 15px 0;
  position: -webkit-sticky;
  position: sticky;
  top: 0px;
  /* background: white; */
  height: 560px;
}

/* RTL support - keep menu on left side */
[dir="rtl"] .static-details {
  flex-direction: row; /* Keep row direction */
}

[dir="rtl"] .static-details .menu-wrapper {
  order: -1; /* Keep menu first (left side) */
}

[dir="rtl"] .menu-wrapper {
  border-radius: 0 15px 15px 0; /* Keep left border radius */
}

@media (max-width: 768px) {
  .static-details {
    justify-content: center;
    flex-direction: column;
    height: max-content;
    margin-bottom: 100px;
    gap: 20px;
    justify-self: center;
    padding-left: 20px;
    height: max-content;
    overflow-x: hidden !important;
    width: 100% !important;
    max-width: 100% !important;
  }
  .menu-wrapper {
    width: 99% !important;
    max-width: 99% !important;
    min-width: auto !important;
    border-radius: 0;
    position: relative;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 10px;
    height: max-content;
  }
  .v-window__container {
    min-width: 100%;
    width: 100%;
    max-width: 100%;
    height: max-content;
  }
  .show-option {
    width: 95% !important;
    min-width: auto !important;
    height: auto;
    position: relative;
    max-height: max-content;
    min-height: min-content;
    margin-bottom: 10px;
    justify-content: center;
  }
  .show-qr {
    position: relative;
    width: 90% !important;
    min-width: auto !important;
    min-height: max-content;
    height: v-bind("showSaveOptions ? 460 : 400") !important;
    max-height: max-content;
    transition: height 0.3s ease;
    overflow: visible !important;
    z-index: 10;
  }
  .show-qr img {
    width: 98% !important;
    height: 98% !important;
  }
  .save-button {
    width: 90%;
  }
  .show-option-cont {
    width: 98%;
    justify-items: center;
  }
  .menu-wrapper-cont {
    width: 100%;
    position: fixed !important;
    bottom: 0px !important;
    z-index: 1000;
    position: fixed !important;
    height: max-content !important;
  }
  .menu-container {
    height: max-content;
    width: 90%;
  }
}
@media (max-width: 425px) {
  .show-option-cont,
  .menu-wrapper-cont {
    width: 98%;
    justify-items: center;
  }
  .menu-wrapper-cont {
    position: fixed !important;
    z-index: 1000;
  }
  .menu-container {
    height: max-content;
    width: 90%;
  }
  .menu-wrapper {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 99%;
  }
  .show-option {
    width: 100%;
    min-width: 200px;
    padding: 10px;
  }
  .show-qr {
    width: 100%;
    min-width: 200px;
    padding: 10px;
    height: v-bind("showSaveOptions ? 460 : 400") !important;
    min-height: max-content;
    max-height: max-content;
    transition: height 0.3s ease;
    overflow: visible !important;
    z-index: 10;
  }
  .static-details {
    flex-direction: column;
    flex-wrap: wrap;
    height: max-content;
  }
}

/* Mobile small devices (320px and below) */
@media (max-width: 320px) {
  .show-qr {
    width: 95% !important;
    min-width: 280px !important;
    padding: 8px !important;
    margin: 10px !important;
    height: v-bind("showSaveOptions ? 460 : 400") !important;
    min-height: max-content;
    max-height: max-content;
    transition: height 0.3s ease;
    overflow: visible !important;
    z-index: 10;
  }

  .show-qr img {
    width: 250px !important;
    height: 250px !important;
    margin: 5px !important;
  }

  .save-button {
    padding-left: 60px !important;
    padding-right: 60px !important;
    font-size: 14px !important;
  }

  .static-details {
    padding-left: 10px !important;
    padding-right: 10px !important;
  }
}
.save-options {
  display: flex;
  flex-direction: row;
  gap: 20px;
  justify-content: center;
  justify-items: center;
  align-items: center;
  margin-top: 25px;
  margin-bottom: 25px;
  padding: 20px;
  min-height: 80px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  backdrop-filter: blur(10px);
  position: relative;
  z-index: 20;
}

@media (max-width: 768px) {
  .save-options {
    flex-wrap: wrap;
    gap: 15px;
    padding: 15px;
    margin-top: 20px;
    margin-bottom: 20px;
  }
}

@media (max-width: 425px) {
  .save-options {
    flex-wrap: wrap;
    gap: 10px;
    padding: 10px;
    margin-top: 15px;
    margin-bottom: 15px;
  }

  .save-options .v-btn {
    padding: 6px !important;
    min-width: 45px !important;
    min-height: 45px !important;
  }

  .save-options .v-btn svg {
    height: 2.5em !important;
    width: 2.5em !important;
  }
}
.save-options .v-btn svg {
  color: white !important;
  font-size: 16px;
  height: 3em !important;
  width: 3em !important;
}
.save-options .v-btn {
  padding: 8px !important;
  min-width: 50px !important;
  min-height: 50px !important;
}

.save-options .v-btn:hover {
  /* background: v-bind("settings.butttonColor") !important; */
  /* border-radius: 50%; */
  /* padding: 5px; */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  scale: 1.1;
}
.qrCodeImage {
  padding: 0px !important;
}
.show-option-cont,
.menu-wrapper-cont {
  height: 100%;
  justify-items: center;
}
</style>
