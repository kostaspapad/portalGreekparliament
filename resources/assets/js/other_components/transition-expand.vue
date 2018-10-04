<template>
  <transition
    name="expand"
    @enter="enter"
    @after-enter="afterEnter"
    @leave="leave"
  >
    <slot/>
  </transition>
</template>

<style lang="scss" scoped>
    * {
        will-change: height;
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
    }
    .expand-enter-active,
    .expand-leave-active {
        transition: height .3s ease-in-out;
        overflow: hidden;
    }

    .expand-enter,
    .expand-leave-to {
        height: 0;
    }
</style>


<script>
    export default {
        name: 'TransitionExpand',
        methods: {
            enter(element) {
                const width = getComputedStyle(element).width

                element.style.width = width
                element.style.position = 'absolute'
                element.style.visibility = 'hidden'
                element.style.height = 'auto'

                const height = getComputedStyle(element).height

                element.style.width = null
                element.style.position = null
                element.style.visibility = null
                element.style.height = 0

                // Trigger the animation.
                // We use `setTimeout` because we need
                // to make sure the browser has finished
                // painting after setting the `height`
                // to `0` in the line above.
                setTimeout(() => {
                    element.style.height = height
                })
            },
            afterEnter(element) {
                element.style.height = 'auto'
            },
            leave(element) {
                const height = getComputedStyle(element).height
                
                element.style.height = height

                setTimeout(() => {
                    element.style.height = 0
                })
            }
        }
    }
</script>