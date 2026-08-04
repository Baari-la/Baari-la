/*
|--------------------------------------------------------------------------
| DIGESTEX Checkbox Card™
|--------------------------------------------------------------------------
|
| Reusable checkbox card used across the DIGESTEX
| onboarding framework.
|
| Used by:
|
| • Business Activities
| • Business Strategy
| • Sustainability
| • Capability
| • Certification
| • Markets
|
|--------------------------------------------------------------------------
*/

import { CheckCircle2 } from "lucide-react";

export default function CheckboxCard({
    title,
    description = "",
    checked = false,
    disabled = false,
    icon: Icon = null,
    badge = null,
    onChange = () => {},
}) {
    return (
        <label
            className={`
                group
                relative
                block
                cursor-pointer
                rounded-2xl
                border
                p-5
                transition-all
                duration-200

                ${
                    checked
                        ? "border-indigo-500 bg-indigo-50 shadow-sm"
                        : "border-slate-200 bg-white hover:border-indigo-300 hover:bg-slate-50"
                }

                ${disabled ? "cursor-not-allowed opacity-60" : ""}
            `}
        >
            {/* Hidden Checkbox */}

            <input
                type="checkbox"
                checked={checked}
                disabled={disabled}
                onChange={(event) => onChange(event.target.checked)}
                className="sr-only"
            />

            {/* Selected Indicator */}

            <div className="absolute right-4 top-4">
                {checked ? (
                    <CheckCircle2 className="h-6 w-6 text-indigo-600" />
                ) : (
                    <div className="h-6 w-6 rounded-full border-2 border-slate-300 transition group-hover:border-indigo-400" />
                )}
            </div>

            {/* Content */}

            <div className="pr-8">
                {/* Icon */}

                {Icon && (
                    <div className="mb-3 inline-flex rounded-xl bg-indigo-100 p-2">
                        <Icon className="h-5 w-5 text-indigo-600" />
                    </div>
                )}

                {/* Title */}

                <div className="text-base font-bold text-slate-900">
                    {title}
                </div>

                {/* Description */}

                {description && (
                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {description}
                    </p>
                )}

                {/* Badge */}

                {badge && (
                    <div className="mt-4">
                        <span className="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                            {badge}
                        </span>
                    </div>
                )}
            </div>
        </label>
    );
}
