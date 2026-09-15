import { Fragment, useState, type ComponentType } from 'react';
import { ChevronDown } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { cn } from '@/lib/utils';
import { resolveIcon } from '@laravilt/support/lib/icons';
import { getColumnSpanClass, getGridClass, normalizeEntryProps } from '../lib/entries';
import BadgeEntry from './entries/BadgeEntry';
import CodeEntry from './entries/CodeEntry';
import ColorEntry from './entries/ColorEntry';
import IconEntry from './entries/IconEntry';
import ImageEntry from './entries/ImageEntry';
import KeyValueEntry from './entries/KeyValueEntry';
import RepeatableEntry from './entries/RepeatableEntry';
import TextEntry from './entries/TextEntry';

export interface InfoListEntry {
    component: string;
    name: string;
    label: string;
    value?: any;
    state?: any;
    schema?: InfoListEntry[];
    description?: string;
    columns?: number | Record<string, number>;
    columnSpan?: number | string | Record<string, number | string>;
    tabs?: InfoListEntry[];
    [key: string]: any;
}

export interface InfoListProps {
    schema: InfoListEntry[];
}

const componentMap: Record<string, ComponentType<any>> = {
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
};

const getEntryComponent = (componentType: string): ComponentType<any> => {
    return componentMap[componentType] || TextEntry;
};

const LAYOUT_TYPES = ['section', 'grid', 'tabs'];

// Helper to check component type (case insensitive)
const isComponent = (item: InfoListEntry, type: string) => {
    return (item.component || '').toLowerCase() === type.toLowerCase();
};

/** `<component :is="getEntryComponent(entry.component)" :class="…" v-bind="entryProps(entry)" />` */
function renderEntry(entry: InfoListEntry, key: string | number) {
    const Entry = getEntryComponent(entry.component);

    // snake_case keys also exposed as camelCase, state from value ?? state
    return (
        <Entry
            key={key}
            {...normalizeEntryProps(entry)}
            state={entry.value ?? entry.state}
            className={getColumnSpanClass(entry.columnSpan)}
        />
    );
}

interface SectionHeaderProps {
    item: InfoListEntry;
    collapsed: boolean;
    onToggle: () => void;
}

function SectionHeader({ item, collapsed, onToggle }: SectionHeaderProps) {
    if (!(item.label || item.heading)) {
        return null;
    }

    const Icon = item.icon ? resolveIcon(item.icon) : null;
    const collapsible = !!item.collapsible;

    return (
        <header className={cn('px-6 py-4', collapsed ? '' : 'border-b')}>
            <div
                className={cn('flex items-center gap-3', collapsible ? 'cursor-pointer select-none' : '')}
                role={collapsible ? 'button' : undefined}
                tabIndex={collapsible ? 0 : undefined}
                aria-expanded={collapsible ? !collapsed : undefined}
                onClick={onToggle}
                onKeyDown={(event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        onToggle();
                    }
                }}
            >
                {item.icon && Icon && (
                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary flex-shrink-0">
                        <Icon className="h-5 w-5" />
                    </div>
                )}
                <div className="flex-1 min-w-0">
                    <h3 className="leading-none font-semibold">{item.label || item.heading}</h3>
                    {item.description && <p className="mt-1 text-sm text-muted-foreground">{item.description}</p>}
                </div>
                {collapsible && (
                    <ChevronDown
                        className={cn(
                            'h-4 w-4 text-muted-foreground transition-transform duration-200 flex-shrink-0',
                            collapsed ? '-rotate-90 rtl:rotate-90' : '',
                        )}
                    />
                )}
            </div>
        </header>
    );
}

export default function InfoList({ schema }: InfoListProps) {
    // Collapsible sections: start from the section's `collapsed` flag, then follow the user's toggles
    const [sectionCollapsedState, setSectionCollapsedState] = useState<Record<string, boolean>>({});

    const isSectionCollapsed = (item: InfoListEntry, key: string): boolean => {
        if (!item.collapsible) return false;
        return key in sectionCollapsedState ? sectionCollapsedState[key] : !!item.collapsed;
    };

    const toggleSection = (item: InfoListEntry, key: string) => {
        if (!item.collapsible) return;
        setSectionCollapsedState((current) => ({ ...current, [key]: !isSectionCollapsed(item, key) }));
    };

    // Separate layout components from entries
    const layoutComponents =
        !schema || !Array.isArray(schema)
            ? []
            : schema.filter((item) => LAYOUT_TYPES.includes((item.component || '').toLowerCase()));

    const entries =
        !schema || !Array.isArray(schema)
            ? []
            : schema.filter((item) => !LAYOUT_TYPES.includes((item.component || '').toLowerCase()));

    return (
        <div className="space-y-6">
            {/* Render layout components (Section, Grid, Tabs) */}
            {layoutComponents.map((layout, layoutIndex) => {
                const layoutKey = `s${layoutIndex}`;

                return (
                    <Fragment key={layout.label || layout.name}>
                        {isComponent(layout, 'section') ? (
                            /* Section Component */
                            <div className="bg-card text-card-foreground rounded-xl border shadow-sm">
                                <SectionHeader
                                    item={layout}
                                    collapsed={isSectionCollapsed(layout, layoutKey)}
                                    onToggle={() => toggleSection(layout, layoutKey)}
                                />
                                <div className="p-6" style={isSectionCollapsed(layout, layoutKey) ? { display: 'none' } : undefined}>
                                    <div className="space-y-6">
                                        {(layout.schema ?? []).map((item, itemIndex) => (
                                            <Fragment key={item.name || itemIndex}>
                                                {isComponent(item, 'grid') ? (
                                                    /* Nested Grid within Section */
                                                    <div className={cn('grid gap-6', getGridClass(item.columns))}>
                                                        {(item.schema ?? []).map((entry, entryIndex) => renderEntry(entry, entry.name || entryIndex))}
                                                    </div>
                                                ) : (
                                                    /* Direct Entry within Section */
                                                    renderEntry(item, item.name || itemIndex)
                                                )}
                                            </Fragment>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        ) : isComponent(layout, 'grid') ? (
                            /* Grid Component */
                            <div className={cn('grid gap-6', getGridClass(layout.columns))}>
                                {(layout.schema ?? []).map((entry, entryIndex) => renderEntry(entry, entry.name || entryIndex))}
                            </div>
                        ) : isComponent(layout, 'tabs') ? (
                            /* Tabs Component */
                            <Tabs defaultValue={layout.tabs?.[0]?.label || 'tab-0'}>
                                <TabsList>
                                    {(layout.tabs ?? []).map((tab, index) => (
                                        <TabsTrigger key={tab.label || `tab-${index}`} value={tab.label || `tab-${index}`}>
                                            {tab.label}
                                        </TabsTrigger>
                                    ))}
                                </TabsList>
                                {(layout.tabs ?? []).map((tab, index) => (
                                    <TabsContent key={tab.label || `tab-${index}`} value={tab.label || `tab-${index}`} className="mt-6">
                                        <div className="space-y-6">
                                            {/* Recursively render layout components within tabs */}
                                            {(tab.schema ?? []).map((item, itemIndex) => {
                                                const sectionKey = `t${layoutIndex}-${index}-${itemIndex}`;

                                                return (
                                                    <Fragment key={item.name || itemIndex}>
                                                        {isComponent(item, 'section') ? (
                                                            /* Section within Tab */
                                                            <div className="bg-card text-card-foreground rounded-xl border shadow-sm">
                                                                <SectionHeader
                                                                    item={item}
                                                                    collapsed={isSectionCollapsed(item, sectionKey)}
                                                                    onToggle={() => toggleSection(item, sectionKey)}
                                                                />
                                                                <div
                                                                    className="p-6"
                                                                    style={isSectionCollapsed(item, sectionKey) ? { display: 'none' } : undefined}
                                                                >
                                                                    <div className={cn('grid gap-6', getGridClass(item.columns))}>
                                                                        {(item.schema ?? []).map((entry, entryIndex) =>
                                                                            renderEntry(entry, entry.name || entryIndex),
                                                                        )}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        ) : isComponent(item, 'grid') ? (
                                                            /* Grid within Tab */
                                                            <div className={cn('grid gap-6', getGridClass(item.columns))}>
                                                                {(item.schema ?? []).map((entry, entryIndex) => renderEntry(entry, entry.name || entryIndex))}
                                                            </div>
                                                        ) : (
                                                            /* Regular entries within Tab */
                                                            renderEntry(item, item.name || itemIndex)
                                                        )}
                                                    </Fragment>
                                                );
                                            })}
                                        </div>
                                    </TabsContent>
                                ))}
                            </Tabs>
                        ) : null}
                    </Fragment>
                );
            })}

            {/* If there are no layout components, render entries directly without card wrapper */}
            {layoutComponents.length === 0 && entries.length > 0 && (
                <div className="grid gap-6">{entries.map((entry, entryIndex) => renderEntry(entry, entry.name || entryIndex))}</div>
            )}
        </div>
    );
}

export { InfoList };
