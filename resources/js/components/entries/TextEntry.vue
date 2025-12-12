<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Copy } from 'lucide-vue-next'
import * as LucideIcons from 'lucide-vue-next'
import { useNotification } from '@laravilt/notifications/composables/useNotification'

const { notify } = useNotification()

interface TextEntryProps {
  label: string
  state: any
  placeholder?: string
  copyable?: boolean
  limit?: number | null
  wrap?: boolean
  markdown?: boolean
  html?: boolean
  prefix?: string | null
  suffix?: string | null
  badge?: boolean
  color?: string | null
  icon?: string | null
  strikethrough?: boolean
}

const props = withDefaults(defineProps<TextEntryProps>(), {
  placeholder: '-',
  copyable: false,
  limit: null,
  wrap: false,
  markdown: false,
  html: false,
  prefix: null,
  suffix: null,
  badge: false,
  color: null,
  icon: null,
  strikethrough: false,
})

const formattedValue = computed(() => {
  if (props.state === null || props.state === undefined) {
    return props.placeholder
  }

  let result = String(props.state)

  // Apply character limit
  if (props.limit && result.length > props.limit) {
    result = result.substring(0, props.limit) + '...'
  }

  // Add prefix and suffix
  if (props.prefix) {
    result = props.prefix + result
  }
  if (props.suffix) {
    result = result + props.suffix
  }

  return result
})

const isArray = computed(() => Array.isArray(props.state))

const lucideIconComponent = computed(() => {
  if (!props.icon) return undefined

  // Convert kebab-case or any case to PascalCase for Lucide component names
  const pascalCaseName = props.icon
    .split('-')
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1).toLowerCase())
    .join('')

  return (LucideIcons as any)[pascalCaseName]
})

const badgeVariant = computed(() => {
  if (!props.color) return 'secondary'

  const colorMap: Record<string, string> = {
    primary: 'default',
    success: 'success',
    danger: 'destructive',
    warning: 'warning',
    info: 'secondary',
    gray: 'secondary',
    secondary: 'secondary',
  }

  return colorMap[props.color] || 'secondary'
})

const handleCopy = () => {
  if (props.copyable && formattedValue.value && formattedValue.value !== props.placeholder) {
    navigator.clipboard.writeText(String(props.state))
    notify({
      title: 'Copied',
      body: 'Copied to clipboard',
      type: 'success',
    })
  }
}
</script>

<template>
  <div class="flex flex-col gap-1">
    <div class="text-sm font-medium text-foreground">
      {{ label }}
    </div>
    <div class="flex items-start gap-2">
      <!-- Icon (outside badge) -->
      <component
        :is="lucideIconComponent"
        v-if="lucideIconComponent && !badge"
        class="h-4 w-4 shrink-0 mt-0.5 text-muted-foreground"
      />

      <!-- Multiple badges for array values -->
      <div v-if="badge && isArray" class="flex flex-wrap gap-1.5">
        <Badge
          v-for="(item, index) in state"
          :key="index"
          :variant="badgeVariant"
          class="font-normal flex items-center gap-1.5"
        >
          <component
            :is="lucideIconComponent"
            v-if="lucideIconComponent"
            class="h-3 w-3 shrink-0"
          />
          {{ item }}
        </Badge>
      </div>
      <!-- Single badge -->
      <Badge v-else-if="badge" :variant="badgeVariant" class="font-normal flex items-center gap-1.5">
        <component
          :is="lucideIconComponent"
          v-if="lucideIconComponent"
          class="h-3 w-3 shrink-0"
        />
        {{ formattedValue }}
      </Badge>
      <div
        v-else-if="html"
        :class="[
          'text-sm text-muted-foreground',
          wrap ? 'whitespace-normal' : 'truncate',
          strikethrough ? 'line-through' : '',
        ]"
        v-html="formattedValue"
      />
      <span
        v-else
        :class="[
          'text-sm',
          state === null || state === undefined ? 'text-muted-foreground italic' : 'text-foreground',
          wrap ? 'whitespace-normal' : 'truncate',
          strikethrough ? 'line-through' : '',
        ]"
      >
        {{ formattedValue }}
      </span>

      <Button
        v-if="copyable && state !== null && state !== undefined"
        variant="ghost"
        size="icon"
        class="h-6 w-6 shrink-0"
        @click="handleCopy"
      >
        <Copy class="h-3 w-3" />
      </Button>
    </div>
  </div>
</template>
