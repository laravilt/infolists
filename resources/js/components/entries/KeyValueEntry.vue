<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import { Copy } from 'lucide-vue-next'
import { useNotification } from '@laravilt/notifications/composables/useNotification'

const { notify } = useNotification()

interface KeyValueEntryProps {
  label: string
  state: any
  placeholder?: string
  keyLabel?: string
  valueLabel?: string
  copyableKeys?: boolean
  copyableValues?: boolean
}

const props = withDefaults(defineProps<KeyValueEntryProps>(), {
  placeholder: '—',
  keyLabel: 'Key',
  valueLabel: 'Value',
  copyableKeys: false,
  copyableValues: false,
})

const entries = computed(() => {
  if (!props.state || typeof props.state !== 'object') {
    return []
  }

  return Object.entries(props.state).map(([key, value]) => ({
    key,
    value: String(value),
  }))
})

const handleCopyKey = (key: string) => {
  navigator.clipboard.writeText(key)
  notify({
    title: 'Copied',
    body: 'Key copied to clipboard',
    type: 'success',
  })
}

const handleCopyValue = (value: string) => {
  navigator.clipboard.writeText(value)
  notify({
    title: 'Copied',
    body: 'Value copied to clipboard',
    type: 'success',
  })
}
</script>

<template>
  <div class="flex flex-col gap-1">
    <div class="text-sm font-medium text-foreground">
      {{ label }}
    </div>
    <div v-if="entries.length > 0" class="rounded-md border border-border overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-muted/50">
          <tr>
            <th class="px-4 py-2 text-left font-medium text-foreground">
              {{ keyLabel }}
            </th>
            <th class="px-4 py-2 text-left font-medium text-foreground">
              {{ valueLabel }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border">
          <tr
            v-for="(entry, index) in entries"
            :key="index"
            class="hover:bg-muted/30"
          >
            <td class="px-4 py-2">
              <div class="flex items-center gap-2">
                <span class="font-medium text-foreground">{{ entry.key }}</span>
                <Button
                  v-if="copyableKeys"
                  variant="ghost"
                  size="icon"
                  class="h-6 w-6 shrink-0"
                  @click="handleCopyKey(entry.key)"
                >
                  <Copy class="h-3 w-3" />
                </Button>
              </div>
            </td>
            <td class="px-4 py-2">
              <div class="flex items-center gap-2">
                <span class="text-muted-foreground">{{ entry.value }}</span>
                <Button
                  v-if="copyableValues"
                  variant="ghost"
                  size="icon"
                  class="h-6 w-6 shrink-0"
                  @click="handleCopyValue(entry.value)"
                >
                  <Copy class="h-3 w-3" />
                </Button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <span v-else class="text-sm text-muted-foreground italic">
      {{ placeholder }}
    </span>
  </div>
</template>
