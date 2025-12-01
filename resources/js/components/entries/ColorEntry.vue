<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import { Copy } from 'lucide-vue-next'
import { useNotification } from '@laravilt/notifications/composables/useNotification'

const { notify } = useNotification()

interface ColorEntryProps {
  label: string
  state: any
  placeholder?: string
  copyable?: boolean
  showLabel?: boolean
  size?: string | null
}

const props = withDefaults(defineProps<ColorEntryProps>(), {
  placeholder: '—',
  copyable: true,
  showLabel: true,
  size: null,
})

const sizeClass = computed(() => {
  switch (props.size) {
    case 'xs':
      return 'h-4 w-4'
    case 'sm':
      return 'h-6 w-6'
    case 'md':
      return 'h-8 w-8'
    case 'lg':
      return 'h-10 w-10'
    case 'xl':
      return 'h-12 w-12'
    default:
      return 'h-8 w-8'
  }
})

const handleCopy = () => {
  if (props.copyable && props.state) {
    navigator.clipboard.writeText(props.state)
    notify({
      title: 'Copied',
      body: 'Color copied to clipboard',
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
    <div v-if="state" class="flex items-center gap-2">
      <div
        :class="[sizeClass, 'rounded border border-border shrink-0']"
        :style="{ backgroundColor: state }"
      />
      <span v-if="showLabel" class="text-sm text-foreground font-mono">
        {{ state }}
      </span>
      <Button
        v-if="copyable"
        variant="ghost"
        size="icon"
        class="h-6 w-6 shrink-0"
        @click="handleCopy"
      >
        <Copy class="h-3 w-3" />
      </Button>
    </div>
    <span v-else class="text-sm text-muted-foreground italic">
      {{ placeholder }}
    </span>
  </div>
</template>
