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
    // A state can be one URL or a list of URLs; fall back to the default image
    const sources = (Array.isArray(state) ? state : [state]).filter(
        (src: unknown): src is string => typeof src === 'string' && src !== '',
    );
    const images: string[] = sources.length === 0 && defaultImage ? [defaultImage] : sources;

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
            <div className="flex flex-wrap items-center gap-2">
                {images.length > 0 ? (
                    images.map((src, index) => <img key={index} src={src} alt={alt || label} style={imageStyle} className={imageClass} />)
                ) : (
                    <span className="text-sm text-muted-foreground italic">{placeholder}</span>
                )}
            </div>
        </div>
    );
}

export { ImageEntry };
