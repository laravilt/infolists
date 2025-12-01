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

const imageSrc = computed(() => {
  return props.state || props.defaultImage
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
    <div class="flex items-center">
      <img
        v-if="imageSrc"
        :src="imageSrc"
        :alt="alt || label"
        :style="imageStyle"
        :class="imageClass"
      />
      <span v-else class="text-sm text-muted-foreground italic">
        {{ placeholder }}
      </span>
    </div>
  </div>
</template>
