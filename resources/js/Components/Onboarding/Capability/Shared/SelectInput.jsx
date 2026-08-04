import { ChevronDown, List } from "lucide-react";

export default function SelectInput({
    icon: Icon = List,
    label,
    value,
    onChange,
    options = [],
    placeholder = "Select...",
    required = false,
    disabled = false,
}) {
    return (
        <div className="space-y-2">
            {label && (
                <label className="block text-sm font-semibold text-slate-700">
                    {label}

                    {required && <span className="ml-1 text-red-500">*</span>}
                </label>
            )}

            <div
                className={`
                    relative
                    flex
                    items-center
                    rounded-xl
                    border
                    bg-white
                    transition-all

                    ${
                        disabled
                            ? "border-slate-200 bg-slate-100"
                            : "border-slate-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-100"
                    }
                `}
            >
                <div className="px-4 text-slate-400">
                    <Icon className="h-5 w-5" />
                </div>

                <select
                    value={value ?? ""}
                    disabled={disabled}
                    onChange={(e) => onChange?.(e.target.value)}
                    className="
                        w-full
                        appearance-none
                        bg-transparent
                        py-3
                        pr-12
                        text-slate-700
                        outline-none
                        disabled:cursor-not-allowed
                    "
                >
                    <option value="">{placeholder}</option>

                    {options.map((option) => {
                        const item =
                            typeof option === "string"
                                ? {
                                      label: option,
                                      value: option,
                                  }
                                : option;

                        return (
                            <option key={item.value} value={item.value}>
                                {item.label}
                            </option>
                        );
                    })}
                </select>

                <ChevronDown
                    className="
                        pointer-events-none
                        absolute
                        right-4
                        h-5
                        w-5
                        text-slate-400
                    "
                />
            </div>
        </div>
    );
}
