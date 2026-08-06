import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";

export default function OnboardingLayout({ children, currentStep }) {
    return (
        <div className="min-h-screen bg-slate-50 flex flex-col">
            {/* <OnboardingNavbar currentStep={currentStep} /> */}

            <main className="flex-1">{children}</main>
        </div>
    );
}
