import { Check } from "lucide-react";

export default function CheckboxCard({
    title,
    description,
    checked = false,
    onChange,
    disabled = false,
}) {
    return (
        <button
            type="button"
            disabled={disabled}
            onClick={() => !disabled && onChange?.(!checked)}
            className={`
                group
                w-full
                rounded-2xl
                border
                p-5
                text-left
                transition-all
                duration-200

                ${
                    checked
                        ? "border-indigo-500 bg-indigo-50 shadow-md"
                        : "border-slate-200 bg-white hover:border-indigo-300 hover:shadow-sm"
                }

                ${disabled ? "cursor-not-allowed opacity-60" : "cursor-pointer"}
            `}
        >
            <div className="flex items-start justify-between gap-4">
                <div className="flex-1">
                    <h4
                        className={`
                            text-base
                            font-bold

                            ${checked ? "text-indigo-700" : "text-slate-900"}
                        `}
                    >
                        {title}
                    </h4>

                    {description && (
                        <p className="mt-2 text-sm leading-6 text-slate-500">
                            {description}
                        </p>
                    )}
                </div>

                <div
                    className={`
                        flex
                        h-7
                        w-7
                        items-center
                        justify-center
                        rounded-full
                        border
                        transition-all

                        ${
                            checked
                                ? "border-indigo-600 bg-indigo-600"
                                : "border-slate-300 bg-white"
                        }
                    `}
                >
                    {checked && <Check className="h-4 w-4 text-white" />}
                </div>
            </div>
        </button>
    );
}
