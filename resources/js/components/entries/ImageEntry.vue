<script setup lang="ts">
import { computed } from 'vue'

interface ImageEntryProps {
  label: string
  state: any
  placeholder?: string
  width?: number | null
  height?: number | null
  rounded?: boolean
  circular?: boolean
  alt?: string | null
  defaultImage?: string | null
}

const props = withDefaults(defineProps<ImageEntryProps>(), {
  placeholder: '—',
  width: null,
  height: null,
  rounded: false,
  circular: false,
  alt: null,
  defaultImage: null,
})

// A state can be one URL or a list of URLs; fall back to the default image
const images = computed<string[]>(() => {
  const list = (Array.isArray(props.state) ? props.state : [props.state])
    .filter((src: unknown): src is string => typeof src === 'string' && src !== '')

  if (list.length === 0 && props.defaultImage) {
    return [props.defaultImage]
  }

  return list
})

const imageStyle = computed(() => {
  const style: Record<string, string> = {}

  if (props.width) {
    style.width = `${props.width}px`
  }
  if (props.height) {
    style.height = `${props.height}px`
  }

  return style
})

const imageClass = computed(() => {
  const classes = ['object-cover']

  if (props.circular) {
    classes.push('rounded-full')
  } else if (props.rounded) {
    classes.push('rounded-md')
  }

  return classes.join(' ')
})
</script>

<template>
  <div class="flex flex-col gap-1">
    <div class="text-sm font-medium text-foreground">
      {{ label }}
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <img
        v-for="(src, index) in images"
        :key="index"
        :src="src"
        :alt="alt || label"
        :style="imageStyle"
        :class="imageClass"
      />
      <span v-if="images.length === 0" class="text-sm text-muted-foreground italic">
        {{ placeholder }}
      </span>
    </div>
  </div>
</template>
