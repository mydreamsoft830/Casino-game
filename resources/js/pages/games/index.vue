<template>
  <div
    class="fill-height"
    :class="{ 'static-background': gameBackgroundImageUrl }"
    :style="{ backgroundImage: gameBackgroundImageUrl ? `url(${gameBackgroundImageUrl})` : 'none' }"
  >
    <animated-background v-if="!gameBackgroundImageUrl" />
    <component :is="gameComponent" :class="['game-container', `game-${$route.params.game}`]" />
    <game-menu :provably-fair-enabled="!isMultiplayerGame($route.params.game)" />
    <game-feed />
  </div>
</template>

<script>
import { config } from '~/plugins/config'
import AnimatedBackground from '~/components/AnimatedBackground'
import GameMenu from '~/components/Games/GameMenu'
import GameFeed from '~/components/GameFeed'

export default {
  middleware: ['auth', 'verified', '2fa_passed', 'game'],

  components: { GameFeed, AnimatedBackground, GameMenu },

  metaInfo () {
    return { title: this.gameComponent ? this.$t(this.gameComponent.name) : '' }
  },

  data () {
    return {
      gameComponent: null,
      gameBackgroundImageUrl: null
    }
  },

  created () {
    // fetch user info when any game page is open just in case user balance was updated and user didn't refresh the page
    this.$store.dispatch('auth/fetchUser')
    this.initGameComponent(this.$route.params.game, this.$route.params.slug)
  },

  methods: {
    isMultiplayerGame (gamePackageId) {
      return gamePackageId.startsWith('multiplayer-')
    },
    getGameBackgroundImageUrl (gamePackageId, slug) {
      const cfg = config(`${gamePackageId}`)
      return cfg.variations && slug ? cfg.variations[cfg.variations.findIndex(o => o.slug === slug)].background : cfg.background
    },
    async initGameComponent (gamePackageId, slug) {
      // create provably fair game for single player games
      if (!this.isMultiplayerGame(gamePackageId)) {
        this.$store.dispatch('provably-fair/create', { key: gamePackageId })
      }

      // dynamically load game component
      const module = await import(/* webpackChunkName: 'packages/[request]' */`packages/${gamePackageId}/resources/js/pages/game`)

      this.gameComponent = module.default
      this.gameBackgroundImageUrl = this.getGameBackgroundImageUrl(gamePackageId, slug)
    }
  },

  async beforeRouteUpdate (to, from, next) {
    await this.initGameComponent(to.params.game, to.params.slug)

    next()
  }
}
</script>
