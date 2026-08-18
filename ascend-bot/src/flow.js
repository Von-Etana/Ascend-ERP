// All conversation copy and branching logic lives here.
// This is the file to edit when the menu text or flow needs to change.

const MENU_TEXT = `Welcome to Ascend Systems — Nigeria's solar, security & automation partner.

We've powered 150,000+ homes and businesses across Nigeria with solar, CCTV, smart automation, and network infrastructure.

How can we help you today? Reply with a number:

1️⃣ Solar power for my home or business
2️⃣ Security systems (CCTV, access control, cybersecurity)
3️⃣ Smart home / building automation
4️⃣ Solar financing — check what I qualify for
5️⃣ Join our dealer network
6️⃣ Talk to a human

Reply "menu" anytime to see this again.`;

const UNRECOGNIZED_TEXT = `Sorry, I didn't catch that. Here's the menu again:\n\n${MENU_TEXT}`;

// --- Helpers ---------------------------------------------------------

function normalize(text) {
  return (text || "").trim().toLowerCase();
}

function matchesAny(text, keywords) {
  return keywords.some((k) => text.includes(k));
}

// --- Branch content ----------------------------------------------------

const BRANCHES = {
  solar: {
    label: "Solar",
    intro: `Great — solar can be sized for your home or for a business/factory.

Reply:
A) Residential (home)
B) Commercial / Industrial`,
  },
  solar_residential:
    `We design 3kVA–30kVA systems custom-fit to Nigerian residential load profiles — built to beat grid blackouts permanently.

Next step: we'll get your details across to our team for a free site audit.`,
  solar_commercial:
    `Commercial & industrial solar typically cuts diesel/grid costs by 40–70%, pays back in 3–5 years, and runs 25+ years with 99.5%+ uptime.

Next step: we'll get your details across to our team for a free site audit.`,
  security: {
    label: "Security",
    intro: `We offer three security services:

A) CCTV systems (HD cameras, 24/7 monitoring, cloud storage)
B) Access control (biometrics, keycards, mobile access)
C) Cybersecurity (firewalls, threat detection, security audits)

Reply A, B, or C — or "all" if you'd like a full security audit.`,
  },
  automation: {
    label: "Automation",
    intro: `Smart automation for your space — reply:

A) Residential (smart home, security automation, energy management)
B) Commercial (building management, access control, smart lighting/AV)`,
  },
  automation_residential:
    `Residential automation covers smart home control, automated gates/locks, and energy-efficient lighting & HVAC scheduling.

Next step: we'll get your details across for a free site survey.`,
  automation_commercial:
    `Commercial automation covers building management systems (BMS), access control, and occupancy-based lighting/AV for offices and enterprises.

Next step: we'll get your details across for a free site survey.`,
  financing: {
    label: "Financing",
    intro: `Let's check what you qualify for. Are you:

A) Individual
B) Business owner
C) Corporate entity`,
  },
  financing_result:
    `We work with Stanbic IBTC, Access Bank, and Providus Bank on solar asset financing — tenors up to 60 months, and equity requirements starting from 0% depending on the bank.

Next step: we'll get your details across so our team can confirm which bank fits you best.`,
  dealer:
    `Ascend Systems runs Nigeria's fastest-growing solar dealer network, with factory-direct margins on premium LithTech equipment.

Next step: we'll pass your details to our partnerships team.`,
  human: `No problem — let's get you connected to a member of our team.`,
};

// --- State machine -------------------------------------------------------
// Returns { reply, session } — caller is responsible for persisting session.

function route(session, rawText) {
  const text = normalize(rawText);

  if (text === "menu") {
    session.state = "MENU";
    session.data = {};
    return { reply: MENU_TEXT, session };
  }

  switch (session.state) {
    case "START": {
      session.state = "MENU";
      return { reply: MENU_TEXT, session };
    }

    case "MENU": {
      if (text === "1" || matchesAny(text, ["solar"])) {
        session.state = "SOLAR_TYPE";
        session.data.interest = "Solar";
        return { reply: BRANCHES.solar.intro, session };
      }
      if (text === "2" || matchesAny(text, ["security", "cctv"])) {
        session.state = "SECURITY_PICK";
        session.data.interest = "Security";
        return { reply: BRANCHES.security.intro, session };
      }
      if (text === "3" || matchesAny(text, ["automation", "smart home"])) {
        session.state = "AUTOMATION_TYPE";
        session.data.interest = "Automation";
        return { reply: BRANCHES.automation.intro, session };
      }
      if (text === "4" || matchesAny(text, ["financ", "loan", "eligib"])) {
        session.state = "FINANCING_TYPE";
        session.data.interest = "Financing";
        return { reply: BRANCHES.financing.intro, session };
      }
      if (text === "5" || matchesAny(text, ["dealer"])) {
        session.state = "LEAD_NAME";
        session.data.interest = "Dealer network";
        return {
          reply: `${BRANCHES.dealer}\n\nFirst, what's your name?`,
          session,
        };
      }
      if (text === "6" || matchesAny(text, ["human", "agent", "talk"])) {
        session.state = "LEAD_NAME";
        session.data.interest = "Direct human request";
        session.data.priority = "high";
        return {
          reply: `${BRANCHES.human}\n\nFirst, what's your name?`,
          session,
        };
      }
      return { reply: UNRECOGNIZED_TEXT, session };
    }

    case "SOLAR_TYPE": {
      if (matchesAny(text, ["a", "residential", "home"])) {
        session.data.interest = "Solar — residential";
        session.state = "LEAD_NAME";
        return {
          reply: `${BRANCHES.solar_residential}\n\nWhat's your name?`,
          session,
        };
      }
      if (matchesAny(text, ["b", "commercial", "industrial"])) {
        session.data.interest = "Solar — commercial/industrial";
        session.state = "LEAD_NAME";
        return {
          reply: `${BRANCHES.solar_commercial}\n\nWhat's your name?`,
          session,
        };
      }
      return {
        reply: `Please reply A for Residential or B for Commercial/Industrial.`,
        session,
      };
    }

    case "SECURITY_PICK": {
      if (matchesAny(text, ["a", "cctv"])) session.data.interest = "Security — CCTV";
      else if (matchesAny(text, ["b", "access"])) session.data.interest = "Security — Access control";
      else if (matchesAny(text, ["c", "cyber"])) session.data.interest = "Security — Cybersecurity";
      else if (matchesAny(text, ["all"])) session.data.interest = "Security — Full audit";
      else return { reply: `Please reply A, B, C, or "all".`, session };

      session.state = "LEAD_NAME";
      return {
        reply: `Got it. Let's get you a free quote — what's your name?`,
        session,
      };
    }

    case "AUTOMATION_TYPE": {
      if (matchesAny(text, ["a", "residential", "home"])) {
        session.data.interest = "Automation — residential";
        session.state = "LEAD_NAME";
        return {
          reply: `${BRANCHES.automation_residential}\n\nWhat's your name?`,
          session,
        };
      }
      if (matchesAny(text, ["b", "commercial"])) {
        session.data.interest = "Automation — commercial";
        session.state = "LEAD_NAME";
        return {
          reply: `${BRANCHES.automation_commercial}\n\nWhat's your name?`,
          session,
        };
      }
      return {
        reply: `Please reply A for Residential or B for Commercial.`,
        session,
      };
    }

    case "FINANCING_TYPE": {
      if (matchesAny(text, ["a", "individual"])) session.data.buyerType = "Individual";
      else if (matchesAny(text, ["b", "business"])) session.data.buyerType = "Business owner";
      else if (matchesAny(text, ["c", "corporate"])) session.data.buyerType = "Corporate entity";
      else return { reply: `Please reply A, B, or C.`, session };

      session.state = "LEAD_NAME";
      return {
        reply: `${BRANCHES.financing_result}\n\nWhat's your name?`,
        session,
      };
    }

    case "LEAD_NAME": {
      session.data.name = rawText.trim();
      session.state = session.data.buyerType ? "LEAD_LOCATION" : "LEAD_TYPE";
      if (session.state === "LEAD_LOCATION") {
        return { reply: `Thanks ${session.data.name}. What city/area are you in?`, session };
      }
      return {
        reply: `Thanks ${session.data.name}. Are you reaching out as an Individual, Business owner, or Corporate entity?`,
        session,
      };
    }

    case "LEAD_TYPE": {
      session.data.buyerType = rawText.trim();
      session.state = "LEAD_LOCATION";
      return { reply: `Got it. What city/area are you in?`, session };
    }

    case "LEAD_LOCATION": {
      session.data.location = rawText.trim();
      session.state = "DONE";
      return {
        reply: `Perfect — thanks ${session.data.name}. A member of our team will reach out shortly about ${session.data.interest}. You can reply "menu" anytime to explore something else.`,
        session,
        leadComplete: true,
      };
    }

    case "DONE": {
      session.state = "MENU";
      return { reply: MENU_TEXT, session };
    }

    default: {
      session.state = "MENU";
      return { reply: MENU_TEXT, session };
    }
  }
}

module.exports = { route, MENU_TEXT };
