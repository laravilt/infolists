<script setup lang="ts">
import { computed, ref } from 'vue'
import TextEntry from './entries/TextEntry.vue'
import IconEntry from './entries/IconEntry.vue'
import ImageEntry from './entries/ImageEntry.vue'
import ColorEntry from './entries/ColorEntry.vue'
import CodeEntry from './entries/CodeEntry.vue'
import KeyValueEntry from './entries/KeyValueEntry.vue'
import RepeatableEntry from './entries/RepeatableEntry.vue'
import BadgeEntry from './entries/BadgeEntry.vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { ChevronDown } from 'lucide-vue-next'
import * as LucideIcons from 'lucide-vue-next'
import { getColumnSpanClass, getGridClass, normalizeEntryProps } from '../lib/entries'

interface InfoListEntry {
  component: string
  name: string
  label: string
  value?: any
  state?: any
  schema?: InfoListEntry[]
  description?: string
  columns?: number | Record<string, number>
  columnSpan?: number | string | Record<string, number | string>
  tabs?: InfoListEntry[]
  [key: string]: any
}

interface InfoListProps {
  schema: InfoListEntry[]
}

const props = defineProps<InfoListProps>()

const componentMap: Record<string, any> = {
  TextEntry,
  IconEntry,
  ImageEntry,
  ColorEntry,
  CodeEntry,
  KeyValueEntry,
  RepeatableEntry,
  BadgeEntry,
  // The server sends snake_case types (Laravilt\Support\Component::getComponentType)
  text_entry: TextEntry,
  icon_entry: IconEntry,
  image_entry: ImageEntry,
  color_entry: ColorEntry,
  code_entry: CodeEntry,
  key_value_entry: KeyValueEntry,
  repeatable_entry: RepeatableEntry,
  badge_entry: BadgeEntry,
}

const getEntryComponent = (componentType: string) => {
  return componentMap[componentType] || TextEntry
}

// Props for an entry: snake_case keys also exposed as camelCase, state from value ?? state
const entryProps = (entry: InfoListEntry) => {
  return { ...normalizeEntryProps(entry), state: entry.value ?? entry.state }
}

const LAYOUT_TYPES = ['section', 'grid', 'tabs']

// Separate layout components from entries
const layoutComponents = computed(() => {
  if (!props.schema || !Array.isArray(props.schema)) return []
  return props.schema.filter(item => LAYOUT_TYPES.includes((item.component || '').toLowerCase()))
})

const entries = computed(() => {
  if (!props.schema || !Array.isArray(props.schema)) return []
  return props.schema.filter(item => !LAYOUT_TYPES.includes((item.component || '').toLowerCase()))
})

// Helper to check component type (case insensitive)
const isComponent = (item: InfoListEntry, type: string) => {
  return (item.component || '').toLowerCase() === type.toLowerCase()
}

// Collapsible sections: start from the section's `collapsed` flag, then follow the user's toggles
const sectionCollapsedState = ref<Record<string, boolean>>({})

const isSectionCollapsed = (item: InfoListEntry, key: string) => {
  if (!item.collapsible) return false
  return key in sectionCollapsedState.value ? sectionCollapsedState.value[key] : !!item.collapsed
}

const toggleSection = (item: InfoListEntry, key: string) => {
  if (!item.collapsible) return
  sectionCollapsedState.value = { ...sectionCollapsedState.value, [key]: !isSectionCollapsed(item, key) }
}

// Convert kebab-case or snake_case icon names to PascalCase for lucide-vue-next
const getIconComponent = (iconName: string) => {
  if (!iconName) return null

  // Convert formats like 'user', 'id-card', 'map-pin' to PascalCase
  const pascalCase = iconName
    .split(/[-_]/)
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join('')

  return (LucideIcons as any)[pascalCase] || null
}
</script>

<template>
  <div class="space-y-6">
    <!-- Render layout components (Section, Grid, Tabs) -->
    <template v-for="(layout, layoutIndex) in layoutComponents" :key="layout.label || layout.name">
      <!-- Section Component -->
      <div v-if="isComponent(layout, 'section')" class="bg-card text-card-foreground rounded-xl border shadow-sm">
        <header
          v-if="layout.label || layout.heading"
          :class="['px-6 py-4', isSectionCollapsed(layout, `s${layoutIndex}`) ? '' : 'border-b']"
        >
          <div
            :class="['flex items-center gap-3', layout.collapsible ? 'cursor-pointer select-none' : '']"
            :role="layout.collapsible ? 'button' : undefined"
            :tabindex="layout.collapsible ? 0 : undefined"
            :aria-expanded="layout.collapsible ? String(!isSectionCollapsed(layout, `s${layoutIndex}`)) : undefined"
            @click="toggleSection(layout, `s${layoutIndex}`)"
            @keydown.enter.prevent="toggleSection(layout, `s${layoutIndex}`)"
            @keydown.space.prevent="toggleSection(layout, `s${layoutIndex}`)"
          >
            <div
              v-if="layout.icon && getIconComponent(layout.icon)"
              class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary flex-shrink-0"
            >
              <component
                :is="getIconComponent(layout.icon)"
                class="h-5 w-5"
              />
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="leading-none font-semibold">{{ layout.label || layout.heading }}</h3>
              <p v-if="layout.description" class="mt-1 text-sm text-muted-foreground">
                {{ layout.description }}
              </p>
            </div>
            <ChevronDown
              v-if="layout.collapsible"
              :class="[
                'h-4 w-4 text-muted-foreground transition-transform duration-200 flex-shrink-0',
                isSectionCollapsed(layout, `s${layoutIndex}`) ? '-rotate-90 rtl:rotate-90' : '',
              ]"
            />
          </div>
        </header>
        <div v-show="!isSectionCollapsed(layout, `s${layoutIndex}`)" class="p-6">
          <div class="space-y-6">
            <template v-for="(item, itemIndex) in layout.schema" :key="item.name || itemIndex">
              <!-- Nested Grid within Section -->
              <div v-if="isComponent(item, 'grid')" class="grid gap-6" :class="getGridClass(item.columns)">
                <component
                  v-for="(entry, entryIndex) in item.schema"
                  :key="entry.name || entryIndex"
                  :is="getEntryComponent(entry.component)"
                  :class="getColumnSpanClass(entry.columnSpan)"
                  v-bind="entryProps(entry)"
                />
              </div>
              <!-- Direct Entry within Section -->
              <component
                v-else
                :is="getEntryComponent(item.component)"
                :class="getColumnSpanClass(item.columnSpan)"
                v-bind="entryProps(item)"
              />
            </template>
          </div>
        </div>
      </div>

      <!-- Grid Component -->
      <div v-else-if="isComponent(layout, 'grid')" class="grid gap-6" :class="getGridClass(layout.columns)">
        <component
          v-for="(entry, entryIndex) in layout.schema"
          :key="entry.name || entryIndex"
          :is="getEntryComponent(entry.component)"
          :class="getColumnSpanClass(entry.columnSpan)"
          v-bind="entryProps(entry)"
        />
      </div>

      <!-- Tabs Component -->
      <Tabs v-else-if="isComponent(layout, 'tabs')" :default-value="layout.tabs?.[0]?.label || 'tab-0'">
        <TabsList>
          <TabsTrigger
            v-for="(tab, index) in layout.tabs"
            :key="tab.label || `tab-${index}`"
            :value="tab.label || `tab-${index}`"
          >
            {{ tab.label }}
          </TabsTrigger>
        </TabsList>
        <TabsContent
          v-for="(tab, index) in layout.tabs"
          :key="tab.label || `tab-${index}`"
          :value="tab.label || `tab-${index}`"
          class="mt-6"
        >
          <div class="space-y-6">
            <!-- Recursively render layout components within tabs -->
            <template v-for="(item, itemIndex) in tab.schema" :key="item.name || itemIndex">
              <!-- Section within Tab -->
              <div v-if="isComponent(item, 'section')" class="bg-card text-card-foreground rounded-xl border shadow-sm">
                <header
                  v-if="item.label || item.heading"
                  :class="['px-6 py-4', isSectionCollapsed(item, `t${layoutIndex}-${index}-${itemIndex}`) ? '' : 'border-b']"
                >
                  <div
                    :class="['flex items-center gap-3', item.collapsible ? 'cursor-pointer select-none' : '']"
                    :role="item.collapsible ? 'button' : undefined"
                    :tabindex="item.collapsible ? 0 : undefined"
                    :aria-expanded="item.collapsible ? String(!isSectionCollapsed(item, `t${layoutIndex}-${index}-${itemIndex}`)) : undefined"
                    @click="toggleSection(item, `t${layoutIndex}-${index}-${itemIndex}`)"
                    @keydown.enter.prevent="toggleSection(item, `t${layoutIndex}-${index}-${itemIndex}`)"
                    @keydown.space.prevent="toggleSection(item, `t${layoutIndex}-${index}-${itemIndex}`)"
                  >
                    <div
                      v-if="item.icon && getIconComponent(item.icon)"
                      class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary flex-shrink-0"
                    >
                      <component
                        :is="getIconComponent(item.icon)"
                        class="h-5 w-5"
                      />
                    </div>
                    <div class="flex-1 min-w-0">
                      <h3 class="leading-none font-semibold">{{ item.label || item.heading }}</h3>
                      <p v-if="item.description" class="mt-1 text-sm text-muted-foreground">
                        {{ item.description }}
                      </p>
                    </div>
                    <ChevronDown
                      v-if="item.collapsible"
                      :class="[
                        'h-4 w-4 text-muted-foreground transition-transform duration-200 flex-shrink-0',
                        isSectionCollapsed(item, `t${layoutIndex}-${index}-${itemIndex}`) ? '-rotate-90 rtl:rotate-90' : '',
                      ]"
                    />
                  </div>
                </header>
                <div v-show="!isSectionCollapsed(item, `t${layoutIndex}-${index}-${itemIndex}`)" class="p-6">
                  <div class="grid gap-6" :class="getGridClass(item.columns)">
                    <component
                      v-for="(entry, entryIndex) in item.schema"
                      :key="entry.name || entryIndex"
                      :is="getEntryComponent(entry.component)"
                      :class="getColumnSpanClass(entry.columnSpan)"
                      v-bind="entryProps(entry)"
                    />
                  </div>
                </div>
              </div>

              <!-- Grid within Tab -->
              <div v-else-if="isComponent(item, 'grid')" class="grid gap-6" :class="getGridClass(item.columns)">
                <component
                  v-for="(entry, entryIndex) in item.schema"
                  :key="entry.name || entryIndex"
                  :is="getEntryComponent(entry.component)"
                  :class="getColumnSpanClass(entry.columnSpan)"
                  v-bind="entryProps(entry)"
                />
              </div>

              <!-- Regular entries within Tab -->
              <component
                v-else
                :is="getEntryComponent(item.component)"
                :class="getColumnSpanClass(item.columnSpan)"
                v-bind="entryProps(item)"
              />
            </template>
          </div>
        </TabsContent>
      </Tabs>
    </template>

    <!-- If there are no layout components, render entries directly without card wrapper -->
    <div v-if="layoutComponents.length === 0 && entries.length > 0" class="grid gap-6">
      <component
        v-for="(entry, entryIndex) in entries"
        :key="entry.name || entryIndex"
        :is="getEntryComponent(entry.component)"
        :class="getColumnSpanClass(entry.columnSpan)"
        v-bind="entryProps(entry)"
      />
    </div>
  </div>
</template>
