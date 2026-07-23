import { Search, X } from "lucide-react";

export default function AdminSearchBar({
    value = "",
    onChange,
    placeholder = "Search...",
    onClear = null,
}) {
    return (
        <div
            className="
                relative
                w-full
            "
        >
            {/* Search Icon */}

            <Search
                className="
                    absolute
                    left-4
                    top-3.5
                    h-5
                    w-5
                    text-slate-400
                "
            />

            {/* Input */}

            <input
                type="text"
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                className="
                    w-full
                    rounded-2xl
                    border
                    bg-white
                    py-3
                    pl-12
                    pr-12
                    outline-none
                    transition
                    focus:border-emerald-500
                    focus:ring-2
                    focus:ring-emerald-100
                "
            />

            {/* Clear Button */}

            {value && onClear && (
                <button
                    type="button"
                    onClick={onClear}
                    className="
                        absolute
                        right-4
                        top-3
                        text-slate-400
                        transition
                        hover:text-slate-700
                    "
                >
                    <X className="h-5 w-5" />
                </button>
            )}
        </div>
    );
}
