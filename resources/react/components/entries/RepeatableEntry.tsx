import { ChevronDown, ChevronRight } from 'lucide-react';
import { useState, type ComponentType } from 'react';
import { cn } from '@/lib/utils';
import { getValueAtPath, normalizeEntryProps } from '../../lib/entries';
import BadgeEntry from './BadgeEntry';
import CodeEntry from './CodeEntry';
import ColorEntry from './ColorEntry';
import IconEntry from './IconEntry';
import ImageEntry from './ImageEntry';
import KeyValueEntry from './KeyValueEntry';
import TextEntry from './TextEntry';

export interface RepeatableEntryProps {
    label: string;
    state: any[];
    placeholder?: string;
    schema?: any[];
    collapsible?: boolean;
    collapsed?: boolean;
    emptyMessage?: string;
    /** Vue fall-through `class`. */
    className?: string;
}

const EMPTY: any[] = [];

const componentMap: Record<string, ComponentType<any>> = {
    TextEntry,
    IconEntry,
    ImageEntry,
    ColorEntry,
    CodeEntry,
    KeyValueEntry,
    BadgeEntry,
    RepeatableEntry,
    // The server sends snake_case types (Laravilt\Support\Component::getComponentType)
    text_entry: TextEntry,
    icon_entry: IconEntry,
    image_entry: ImageEntry,
    color_entry: ColorEntry,
    code_entry: CodeEntry,
    key_value_entry: KeyValueEntry,
    badge_entry: BadgeEntry,
    repeatable_entry: RepeatableEntry,
};

const getEntryComponent = (componentType: string): ComponentType<any> => {
    return componentMap[componentType] || TextEntry;
};

export default function RepeatableEntry({
    label,
    state,
    schema = EMPTY,
    collapsible = false,
    collapsed = false,
    emptyMessage = 'No items',
    className,
}: RepeatableEntryProps) {
    // Items the user toggled away from the default (`collapsed`). Items added later follow the default.
    const [toggledItems, setToggledItems] = useState<Set<number>>(() => new Set());

    const toggleItem = (index: number) => {
        setToggledItems((current) => {
            const next = new Set(current);

            if (next.has(index)) {
                next.delete(index);
            } else {
                next.add(index);
            }

            return next;
        });
    };

    const isExpanded = (index: number) => {
        return collapsed ? toggledItems.has(index) : !toggledItems.has(index);
    };

    const items: any[] = Array.isArray(state) ? state : [];

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="text-sm font-medium text-foreground">{label}</div>
            {items.length > 0 ? (
                <div className="flex flex-col gap-2">
                    {items.map((item, index) => {
                        const Chevron = isExpanded(index) ? ChevronDown : ChevronRight;

                        return (
                            <div key={index} className="rounded-md border border-border overflow-hidden">
                                {collapsible && (
                                    <div
                                        className="flex items-center justify-between px-4 py-3 bg-muted/50 cursor-pointer hover:bg-muted"
                                        role="button"
                                        tabIndex={0}
                                        aria-expanded={isExpanded(index)}
                                        onClick={() => toggleItem(index)}
                                        onKeyDown={(event) => {
                                            if (event.key === 'Enter' || event.key === ' ') {
                                                event.preventDefault();
                                                toggleItem(index);
                                            }
                                        }}
                                    >
                                        <span className="text-sm font-medium text-foreground">Item {index + 1}</span>
                                        <Chevron className="h-4 w-4 text-muted-foreground" />
                                    </div>
                                )}
                                {(!collapsible || isExpanded(index)) && (
                                    <div className="p-4 space-y-4">
                                        {schema.map((entry, entryIndex) => {
                                            const Entry = getEntryComponent(entry.component);

                                            // snake_case keys also exposed as camelCase, state read from the item (dot notation)
                                            return <Entry key={entryIndex} {...normalizeEntryProps(entry)} state={getValueAtPath(item, entry.name)} />;
                                        })}
                                    </div>
                                )}
                            </div>
                        );
                    })}
                </div>
            ) : (
                <span className="text-sm text-muted-foreground italic">{emptyMessage}</span>
            )}
        </div>
    );
}

export { RepeatableEntry };
