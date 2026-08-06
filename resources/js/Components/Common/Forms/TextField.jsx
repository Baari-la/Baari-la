/*
|--------------------------------------------------------------------------
| DIGESTEX Text Field™
|--------------------------------------------------------------------------
|
| Standard Text Field
|
| Used by:
| • Onboarding
| • Company Profile
| • Admin Panel
| • Directory
| • Intelligence
|
|--------------------------------------------------------------------------
*/

export default function TextField({
    label,

    value = "",

    onChange,

    placeholder = "",

    type = "text",

    readOnly = false,

    disabled = false,

    required = false,

    error = "",

    helperText = "",

    className = "",
}) {
    return (
        <div className="space-y-2">
            {/* ===========================================================
                Label
            =========================================================== */}

            {label && (
                <label className="block text-sm font-semibold text-slate-700">
                    {label}

                    {required && <span className="ml-1 text-red-500">*</span>}
                </label>
            )}

            {/* ===========================================================
                Input
            =========================================================== */}

            <input
                type={type}
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                readOnly={readOnly}
                disabled={disabled}
                className={`
                    w-full
                    rounded-xl
                    border
                    px-4
                    py-3
                    outline-none
                    transition-all
                    duration-200

                    ${
                        error
                            ? "border-red-400 focus:border-red-500"
                            : "border-slate-200 focus:border-emerald-500"
                    }

                    ${
                        readOnly
                            ? "bg-slate-100 text-slate-500 cursor-not-allowed"
                            : ""
                    }

                    ${
                        disabled
                            ? "bg-slate-50 opacity-60 cursor-not-allowed"
                            : ""
                    }

                    ${className}
                `}
            />

            {/* ===========================================================
                Helper Text
            =========================================================== */}

            {!error && helperText && (
                <p className="text-xs text-slate-500">{helperText}</p>
            )}

            {/* ===========================================================
                Error
            =========================================================== */}

            {error && (
                <p className="text-xs font-medium text-red-600">{error}</p>
            )}
        </div>
    );
}
