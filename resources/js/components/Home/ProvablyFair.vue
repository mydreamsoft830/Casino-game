<template>
  <v-parallax src="/images/home/provably-fair.jpg" height="350" class="mt-10">
    <v-row align="center" justify="center" class="darken-50">
      <v-col class="text-center">
        <h3 class="display-1 mb-5">
          <v-icon class="display-1" large color="primary">
            mdi-check-decagram
          </v-icon>
          {{ $t('Provably fair') }}
        </h3>
        <div class="title font-weight-thin mb-5">
          {{ $t('All our games are provably fair.') }}
          {{ $t('We do not cheat.') }}
        </div>
        <div class="text-center">
          <v-btn
            :to="{ name: 'page', params: { id: 'provably-fair' } }"
            outlined
            large
            dark
          >
            {{ $t('Learn more') }}
          </v-btn>
        </div>
      </v-col>
    </v-row>
  </v-parallax>
</template>

<style lang="scss" scoped>
.darken-50 {
  background: rgba(0, 0, 0, 0.5);
}
</style>
