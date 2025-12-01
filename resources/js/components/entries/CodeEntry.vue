<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import { Copy } from 'lucide-vue-next'
import { useNotification } from '@laravilt/notifications/composables/useNotification'

const { notify } = useNotification()

interface CodeEntryProps {
  label: string
  state: any
  placeholder?: string
  copyable?: boolean
  language?: string
  maxHeight?: number | null
  lineNumbers?: boolean
}

const props = withDefaults(defineProps<CodeEntryProps>(), {
  placeholder: '—',
  copyable: true,
  language: 'plaintext',
  maxHeight: null,
  lineNumbers: true,
})

const codeStyle = computed(() => {
  const style: Record<string, string> = {}

  if (props.maxHeight) {
    style.maxHeight = `${props.maxHeight}px`
    style.overflow = 'auto'
  }

  return style
})

const codeLines = computed(() => {
  if (!props.state) return []
  return String(props.state).split('\n')
})

const handleCopy = () => {
  if (props.copyable && props.state) {
    navigator.clipboard.writeText(props.state)
    notify({
      title: 'Copied',
      body: 'Code copied to clipboard',
      type: 'success',
    })
  }
}
</script>

<template>
  <div class="flex flex-col gap-1">
    <div class="flex items-center justify-between">
      <div class="text-sm font-medium text-foreground">
        {{ label }}
      </div>
      <Button
        v-if="copyable && state"
        variant="ghost"
        size="icon"
        class="h-6 w-6 shrink-0"
        @click="handleCopy"
      >
        <Copy class="h-3 w-3" />
      </Button>
    </div>
    <div
      v-if="state"
      class="rounded-md border border-border bg-muted/30 overflow-hidden"
      :style="codeStyle"
    >
      <pre class="p-4 text-sm overflow-x-auto"><code class="font-mono"><template v-if="lineNumbers"><span
            v-for="(line, index) in codeLines"
            :key="index"
            class="block"
          ><span class="inline-block w-8 text-muted-foreground select-none text-right mr-4">{{ index + 1 }}</span><span>{{ line }}</span></span></template><template v-else>{{ state }}</template></code></pre>
    </div>
    <span v-else class="text-sm text-muted-foreground italic">
      {{ placeholder }}
    </span>
  </div>
</template>
