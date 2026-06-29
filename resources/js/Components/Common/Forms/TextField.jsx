export default function TextField({
    label,

    value,

    onChange,

    placeholder = "",

    type = "text",
}) {
    return (
        <div>
            {label && (
                <label className="mb-2 block text-sm font-semibold text-slate-700">
                    {label}
                </label>
            )}

            <input
                type={type}
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                className="
                    w-full
                    rounded-xl
                    border
                    border-slate-200
                    px-4
                    py-3
                    outline-none
                    transition
                    focus:border-blue-500
                "
            />
        </div>
    );
}
