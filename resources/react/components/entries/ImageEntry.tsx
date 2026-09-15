import type { CSSProperties } from 'react';
import { cn } from '@/lib/utils';

export interface ImageEntryProps {
    label: string;
    state: any;
    placeholder?: string;
    width?: number | null;
    height?: number | null;
    rounded?: boolean;
    circular?: boolean;
    alt?: string | null;
    defaultImage?: string | null;
    /** Vue fall-through `class`. */
    className?: string;
}

export default function ImageEntry({
    label,
    state,
    placeholder = '—',
    width = null,
    height = null,
    rounded = false,
    circular = false,
    alt = null,
    defaultImage = null,
    className,
}: ImageEntryProps) {
    const imageSrc = state || defaultImage;

    const imageStyle: CSSProperties = {};

    if (width) {
        imageStyle.width = `${width}px`;
    }

    if (height) {
        imageStyle.height = `${height}px`;
    }

    const classes = ['object-cover'];

    if (circular) {
        classes.push('rounded-full');
    } else if (rounded) {
        classes.push('rounded-md');
    }

    const imageClass = classes.join(' ');

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="text-sm font-medium text-foreground">{label}</div>
            <div className="flex items-center">
                {imageSrc ? (
                    <img src={imageSrc} alt={alt || label} style={imageStyle} className={imageClass} />
                ) : (
                    <span className="text-sm text-muted-foreground italic">{placeholder}</span>
                )}
            </div>
        </div>
    );
}

export { ImageEntry };
