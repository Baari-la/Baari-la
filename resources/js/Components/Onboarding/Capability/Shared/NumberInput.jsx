import { Hash } from "lucide-react";

export default function NumberInput({
    icon: Icon = Hash,
    label,
    value,
    onChange,
    placeholder = "",
    suffix = "",
    min,
    max,
    disabled = false,
    required = false,
}) {
    const handleChange = (e) => {
        let val = e.target.value;

        // hanya angka
        val = val.replace(/[^\d]/g, "");

        onChange?.(val);
    };

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

                <input
                    type="text"
                    inputMode="numeric"
                    value={value ?? ""}
                    onChange={handleChange}
                    placeholder={placeholder}
                    disabled={disabled}
                    min={min}
                    max={max}
                    className="
                        w-full
                        bg-transparent
                        py-3
                        pr-4
                        outline-none
                        placeholder:text-slate-400
                    "
                />

                {suffix && (
                    <div className="border-l border-slate-200 px-4 text-sm font-semibold text-slate-500">
                        {suffix}
                    </div>
                )}
            </div>
        </div>
    );
}
